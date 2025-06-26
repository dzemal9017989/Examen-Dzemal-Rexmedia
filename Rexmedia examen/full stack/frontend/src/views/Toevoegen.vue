<template>
  <div style="min-height: 100vh; background-color: white; margin: 0; padding: 0;">
    <!-- Header -->
    <header style="background-color: lime; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
      <h1 style="color: red; font-size: 2rem; margin: 0;">Suriname</h1>
      <nav style="display: flex; gap: 1rem;">
        <button @click="logout" style="color: red; background: none; border: none; font-weight: bold;">Uitloggen</button>
        <button @click="$router.push('/statistieken')" style="color: red; background: none; border: none; font-weight: bold;">Statistiekenpagina</button>
        <button @click="$router.push('/taken')" style="color: red; background: none; border: none; font-weight: bold;">Takenlijst</button>
      </nav>
    </header>

    <!-- Formulier -->
    <main style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 2rem;">
      <h2>Taak aanmaken of bewerken</h2>
      <form @submit.prevent="opslaan" style="background-color: #f1c40f; padding: 2rem; width: 400px;">
        <label style="font-weight: bold;">Titel</label>
        <input type="text" placeholder="Vul hier een titel in" v-model="titel"
               style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <label style="font-weight: bold;">Beschrijving</label>
        <input type="text" placeholder="Vul hier een beschrijving in" v-model="beschrijving"
               style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <label style="font-weight: bold;">Status</label>
        <select v-model="status" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">
          <option value="open">Open</option>
          <option value="in behandeling">In behandeling</option>
          <option value="voltooid">Voltooid</option>
        </select>

        <label style="font-weight: bold;">Deadline</label>
        <input type="date" v-model="deadline" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <div style="text-align: center;">
        <button type="submit" style="background-color: red; color: white; padding: 0.5rem 1.5rem; border: none; font-weight: bold;">
        Opslaan
        </button>
        </div>

      </form>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from '@/axios'

const router = useRouter()

const titel = ref('')
const beschrijving = ref('')
const status = ref('open')
const deadline = ref('')

const logout = async () => {
  await axios.post('/api/logout')
  router.push('/login')
}

const opslaan = async () => {
  // API-call voorbeeld
  const taak = {
    title: titel.value,
    description: beschrijving.value,
    status: status.value,
    deadline: deadline.value
  }

  await axios.post('/api/taken', taak)
  router.push('/taken')
}
</script>
