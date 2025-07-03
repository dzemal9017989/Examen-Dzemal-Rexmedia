import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

// Views
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/Login.vue'
import TakenView from '@/views/TakenView.vue'
import Toevoegen from '../views/Toevoegen.vue'
import Bewerken from '@/views/Bewerken.vue'
import StatistiekenView from '@/views/StatistiekenView.vue'
import UitnodigingenAanmaken from '@/views/UitnodigingenAanmaken.vue'
import Uitnodiging from '@/views/Uitnodiging.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Routes voor ingelogde gebruikers (met navigatie)
    {
      path: '/',
      component: AppLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '', redirect: '/taken' },
        { path: 'taken', name: 'taken', component: TakenView },
        { path: 'toevoegen', name: 'toevoegen', component: Toevoegen, meta: { requiresAdmin: true } },
        { path: 'bewerken/:id', name: 'Bewerken', component: Bewerken },
        { path: 'statistieken', name: 'Statistieken', component: StatistiekenView },
        { path: 'uitnodigingen', name: 'Uitnodigingen', component: UitnodigingenAanmaken, meta: { requiresAdmin: true } }
      ]
    },

    // Login pagina (zonder navigatie)
    {
      path: '/login',
      name: 'login',
      component: AuthLayout,
      meta: { requiresGuest: true },
      children: [
        { path: '', component: LoginView }
      ]
    },

    // Uitnodiging pagina (zonder navigatie)
    {
      path: '/uitnodiging/:token',
      name: 'Uitnodiging',
      component: Uitnodiging,
      meta: { requiresGuest: true }
    }
  ]
})

// Guards - dit zorgt voor beveiliging
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Check of gebruiker ingelogd is
  if (authStore.user === null && !authStore.loading) {
    await authStore.checkAuth()
  }

  // Wacht tot check klaar is
  while (authStore.loading) {
    await new Promise(resolve => setTimeout(resolve, 50))
  }

  const isAuthenticated = authStore.isAuthenticated
  const isAdmin = authStore.isAdmin

  // Als pagina login vereist maar gebruiker niet ingelogd → naar login
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next('/login')
  }

  // Als pagina admin vereist maar gebruiker geen admin → naar taken
  if (to.meta.requiresAdmin && !isAdmin) {
    return next('/taken')
  }

  // Als pagina alleen voor gasten maar gebruiker ingelogd → naar taken
  if (to.meta.requiresGuest && isAuthenticated) {
    return next('/taken')
  }

  next()
})

export default router