import { createRouter, createWebHistory } from 'vue-router'
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
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView // <== deze toevoegen
    },
    {
      path: '/taken',
      name: 'taken',
      component: TakenView // <== deze toevoegen
    },
    {
      path: '/toevoegen',
      name: 'toevoegen',
      component: Toevoegen // <== deze toevoegen
    },
    {
      path: '/bewerken/:id',
      name: 'Bewerken',
      component: Bewerken
    },
    {
      path: '/statistieken',
      name: 'Statistieken',
      component: StatistiekenView
    },
    {
      path: '/uitnodigingen',
      name: 'Uitnodigingen',
      component: UitnodigingenAanmaken
    },
    {
      path: '/uitnodiging/:token',
      name: Uitnodiging,
      component: Uitnodiging
    },
  ]
})

export default router
