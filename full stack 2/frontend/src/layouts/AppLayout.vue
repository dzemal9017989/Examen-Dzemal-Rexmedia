<template>
    <div style="background-color: white; min-height: 100vh;">
      <!-- Header met navigatie -->
      <header style="background-color: lime; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: red; font-size: 2rem; margin: 0;">Suriname</h1>
        <nav style="display: flex; gap: 1rem;">
          <button @click="$router.push('/taken')" style="color: red; background: none; border: none; font-weight: bold;">
            Takenlijst
          </button>
          <button @click="$router.push('/statistieken')" style="color: red; background: none; border: none; font-weight: bold;">
            Statistieken
          </button>
          <button v-if="authStore.isAdmin" @click="$router.push('/uitnodigingen')" style="color: red; background: none; border: none; font-weight: bold;">
            Gebruikers
          </button>
          <button @click="logout" style="color: red; background: none; border: none; font-weight: bold;">
            Uitloggen
          </button>
        </nav>
      </header>
  
      <!-- Hier komen je pagina's -->
      <router-view />
    </div>
    
  </template>
  
  <script setup>
  import { useAuthStore } from '@/stores/auth'
  import { useRouter } from 'vue-router'
  
  const authStore = useAuthStore()
  const router = useRouter()
  
  const logout = async () => {
    await authStore.logout()
    router.push('/login')
  }
  </script>