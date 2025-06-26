<template>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="p-5 rounded" style="background-color: #e8be17; width: 400px;">
      <h2 class="text-center mb-4">Inloggen</h2>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="email"
          type="email"
          class="form-control"
          id="email"
          placeholder="Vul hier je emailadres in"
          required
        />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Wachtwoord</label>
        <input
          v-model="password"
          type="password"
          class="form-control"
          id="password"
          placeholder="Vul hier je wachtwoord in"
          required
        />
      </div>

      <div class="text-danger mb-3" v-if="error">{{ error }}</div>

      <button @click="login" class="btn btn-danger w-100">Inloggen</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '../axios' 
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const login = async () => {
  error.value = ''

  if (!email.value || !password.value) {
    error.value = 'Vul alle velden in.'
    return
  }

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/api/login', {
      email: email.value,
      password: password.value
    })

    router.push('/taken')
  } catch (err) {
    error.value = 'Inloggen mislukt. Controleer je gegevens.'
  }
}

</script>
