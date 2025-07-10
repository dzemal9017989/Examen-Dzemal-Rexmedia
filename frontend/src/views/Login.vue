<template>
  <div class="min-vh-200 d-flex justify-content-center align-items-center">
    <div class="bg-warning p-5 rounded shadow" style="width: 500px;">
      <!-- Title -->
      <h2 class="text-center mb-4">Inloggen</h2>

      <!-- Email input -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input v-model="email" type="email" id="email" class="form-control" placeholder="Vul hier je emailadres in" required />
      </div>

      <!-- Password input -->
      <div class="mb-3">
        <label for="password" class="form-label">Wachtwoord</label>
        <input v-model="password" type="password" id="password" class="form-control" placeholder="Vul hier je wachtwoord in" required />
      </div>

      <!-- Error message -->
      <div class="text-danger mb-3" v-if="error">{{ error }}</div>

      <!-- Login button -->
      <button @click="handleLogin" class="btn btn-danger w-100" :disabled="authStore.loading">
        {{ authStore.loading ? 'Bezig...' : 'Inloggen' }}
      </button>
    </div>
  </div>
</template>



<script setup>
// These imports are for the functioning of the this page
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')

// This const function handles the login part of this form placed in the template tags
const handleLogin = async () => {
  error.value = ''

  // If the email or password is not correct, it wills how a error message
  if (!email.value || !password.value) {
    error.value = 'Vul alle velden in.'
    return
  }

  const result = await authStore.login({
    email: email.value,
    password: password.value
  })

  // If authentication went sucessfully it will send you to /taken in the application
  if (result.success) {
    router.push('/taken')
  } else {
    // If authentication didn't went sucessfully it will show a error message
    error.value = result.message
  }
}
</script>