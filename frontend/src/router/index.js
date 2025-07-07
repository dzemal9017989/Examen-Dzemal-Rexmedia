// Here are the imports for the router and the auth store
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Here are the imports for the layouts
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

// Here the imports for the components of the views directory
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
    // Routes that require authentication and admin rights
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

    // Login page that is ony accessible for guests who are not authenticated
    {
      path: '/login',
      name: 'login',
      component: AuthLayout,
      meta: { requiresGuest: true },
      children: [
        { path: '', component: LoginView }
      ]
    },

    // This route is for new users who get an invitation link by email
    {
      path: '/uitnodiging/:token',
      name: 'Uitnodiging',
      component: Uitnodiging,
      beforeEnter: async (to, from, next) => {
        const authStore = useAuthStore()
        // Check if the user is authenticated
        if (authStore.isAuthenticated) {
          await authStore.logout()
        }
        
    next()
    }
    }
  ]
})

// Router guards are used to protect routes based on authentication and authorization
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // This checks if the user is logged in
  if (authStore.user === null && !authStore.loading) {
    await authStore.checkAuth()
  }

  // This waits till the check is done before you continue with the navigation
  while (authStore.loading) {
    await new Promise(resolve => setTimeout(resolve, 50))
  }
  // This saves if the user is authenticated and if the user is an admin
  const isAuthenticated = authStore.isAuthenticated
  const isAdmin = authStore.isAdmin

  // This checks if the route requires authentication and if the user is not authenticated, it redirects to the login page
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next('/login')
  }

  // This checks if the route requires admin rights and if the user is not an admin, it redirects to the tasks page
  if (to.meta.requiresAdmin && !isAdmin) {
    return next('/taken')
  }

  // This checks if the route is only for guests and if the user is logged in, it redirects to the tasks page
  if (to.meta.requiresGuest && isAuthenticated) {
    return next('/taken')
  }

  // If all checks pass, it allows the navigation to continue
  next()
})

export default router