<template>
  <div style="padding: 2rem; text-align: center;">
    <h1>Welkom bij de Suriname Taken App</h1>
    <p>Hallo {{ authStore.user?.name }}!</p>
    <router-link to="/taken" class="btn btn-primary">Naar Taken</router-link>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>
