<template>
  <div class="bg-white min-vh-100">
    <!-- Bootstrap navbar -->
    <nav class="navbar navbar-expand-lg bg-success px-4">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Title -->
        <h1 class="text-danger fs-2 m-0">Suriname</h1>

        <!-- Navigation buttons -->
        <div class="d-flex gap-3">
          <button @click="$router.push('/taken')" class="btn btn-outline-danger fw-bold border-0 bg-warning">
            Takenlijst
          </button>
          <button @click="$router.push('/statistieken')" class="btn btn-outline-danger fw-bold border-0 bg-warning">
            Statistieken
          </button>
          <button
            v-if="authStore.isAdmin"
            @click="$router.push('/uitnodigingen')"
            class="btn btn-outline-danger fw-bold border-0 bg-warning"
          >
            Gebruikers
          </button>
          <button @click="logout" class="btn btn-outline-danger fw-bold border-0 bg-warning">
            Uitloggen
          </button>
        </div>
      </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
      <router-view />
    </div>
  </div>
</template>

<script setup>
// Import auth store and router
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

// Setup auth store and router
const authStore = useAuthStore()
const router = useRouter()

// Logout function
const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>
