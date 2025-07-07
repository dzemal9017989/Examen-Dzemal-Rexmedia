<template>
  <div class="p-5 rounded" style="background-color: #e8be17; width: 400px;">
    <!--- This is placed a the top of the form -->
    <h2 class="text-center mb-4">Inloggen</h2>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input v-model="email" type="email" class="form-control" placeholder="Vul hier je emailadres in" required />
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Wachtwoord</label>
      <input v-model="password" type="password" class="form-control" placeholder="Vul hier je wachtwoord in" required />
    </div>

    <!--- This displays a error if something went wrong in writing data in the form -->
    <div class="text-danger mb-3" v-if="error">{{ error }}</div>

    <!--- When you click on this button, it will show Bezig... and then you will go to /taken if everything went well -->
    <button @click="handleLogin" class="btn btn-danger w-100" :disabled="authStore.loading">
      {{ authStore.loading ? 'Bezig...' : 'Inloggen' }}
    </button>
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