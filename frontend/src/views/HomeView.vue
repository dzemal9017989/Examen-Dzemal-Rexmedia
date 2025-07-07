<template>
  <!--- This page displays this when you made a password to log in with it as a new active user-->
  <div style="padding: 2rem; text-align: center;">
    <h1>Welkom bij de Suriname Taken App</h1>
    <p>Hallo {{ authStore.user?.name }}!</p>
    <router-link to="/taken" class="btn btn-primary">Naar Taken</router-link>
  </div>
</template>

<script setup>
// This import uses the auth store where the authenticated user is logged in
import { useAuthStore } from '@/stores/auth'

// This const gives you the access to the logged in user such as name and role
const authStore = useAuthStore()
</script>
