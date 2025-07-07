<template>
  <!---- This gives the whole page a white background and a minimum height  -->
    <div style="background-color: white; min-height: 100vh;">
      <!-- Header with navigation -->
      <header style="background-color: lime; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
        <!--- A title with red color that has the title "Suriname" -->
        <h1 style="color: red; font-size: 2rem; margin: 0;">Suriname</h1>
        <!----  Navigation buttons for different sections -->
        <nav style="display: flex; gap: 1rem;">
          <!--- Navigation buttons for tasks and statistics -->
          <button @click="$router.push('/taken')" style="color: red; background: none; border: none; font-weight: bold;">
            Takenlijst
          </button>
          <button @click="$router.push('/statistieken')" style="color: red; background: none; border: none; font-weight: bold;">
            Statistieken
          </button>
          <!---- This only visible for the admin -->
          <button v-if="authStore.isAdmin" @click="$router.push('/uitnodigingen')" style="color: red; background: none; border: none; font-weight: bold;">
            Gebruikers
          </button>
          <!--- This is the button to log out of the application  -->
          <button @click="logout" style="color: red; background: none; border: none; font-weight: bold;">
            Uitloggen
          </button>
        </nav>
      </header>
  
      
      <router-view />
    </div>
    
  </template>
  
  <script setup>
  // This two lines of code import the necessary stores and router for authentication and navigation
  import { useAuthStore } from '@/stores/auth'
  import { useRouter } from 'vue-router'
  
  // This sets up the authentication store and router for the component
  const authStore = useAuthStore()
  const router = useRouter()
  
  // This function handles the logout process by calling the logout method from the auth store and then redirects to the login page
  const logout = async () => {
    await authStore.logout()
    router.push('/login')
  }
  </script>