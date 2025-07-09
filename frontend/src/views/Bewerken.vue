<template>
  <div style="min-height: 100vh; background-color: white; margin: 0; padding: 0;">
    <main style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 2rem;">
      <h2>Taak bewerken</h2>
      <form @submit.prevent="opslaan" style="background-color: #f1c40f; padding: 2rem; width: 400px;">
        
        <!-- Alleen admin mag titel bewerken -->
        <label v-if="user.role === 'admin'" style="font-weight: bold;">Titel</label>
        <input v-if="user.role === 'admin'" type="text" v-model="title" placeholder="Titel" style="width: 100%; margin-bottom: 1rem;" />

        <label v-if="user.role === 'admin'" style="font-weight: bold;">Beschrijving</label>
        <input v-if="user.role === 'admin'" type="text" v-model="description" placeholder="Beschrijving" style="width: 100%; margin-bottom: 1rem;" />

        <!-- Iedereen mag status wijzigen -->
        <label style="font-weight: bold;">Status</label>
        <select v-model="status_id" style="width: 100%; margin-bottom: 1rem;">
          <option :value="1">Open</option>
          <option :value="2">In behandeling</option>
          <option :value="3">Voltooid</option>
        </select>

        <label style="font-weight: bold;">Deadline</label>
        <input type="date" :min="today" v-model="deadline" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">
   

        <!-- Alleen admin mag taak toewijzen -->
        <label v-if="user.role === 'admin'" style="font-weight: bold;">Toewijzen aan gebruiker</label>
        <select v-if="user.role === 'admin'" v-model="user_id" style="width: 100%; margin-bottom: 1rem;">
          <option disabled value="">-- Kies gebruiker --</option>
          <option v-for="g in users" :key="g.id" :value="g.id">
            {{ g.name }}
          </option>
        </select>

        <div v-if="error" style="color: red; font-weight: bold;">{{ error }}</div>

        <div style="text-align: center;">
          <button type="submit" style="background-color: red; color: white; padding: 0.5rem 1.5rem; border: none;">
            Opslaan
          </button>
        </div>
      </form>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/axios'

const today = new Date().toISOString().split('T')[0]



const route = useRoute()
const router = useRouter()

// Form fields
const title = ref('')
const description = ref('')
const status_id = ref(1)
const deadline = ref('')
const user_id = ref(null)
const users = ref([])

const error = ref('')
const user = ref({})



onMounted(async () => {
  try {
    // Haal ingelogde gebruiker op
    const userRes = await axios.get('/api/user')
    user.value = userRes.data

    // Haal taakgegevens op
    const { data } = await axios.get(`/api/taken/${route.params.id}`)
    title.value = data.title
    description.value = data.description
    status_id.value = data.status_id
    deadline.value = data.deadline
    user_id.value = data.user_id

    // Alleen admin: haal alle gebruikers op voor dropdown
    if (user.value.role === 'admin') {
      const res = await axios.get('/api/users') // of '/api/gebruiker-opties'
      users.value = res.data
    }

  } catch (err) {
    error.value = 'Kan taak of gebruikers niet ophalen.'
  }
})

const opslaan = async () => {
  error.value = ''
  try {
    const payload = user.value.role === 'admin'
      ? {
          title: title.value,
          description: description.value,
          status_id: status_id.value,
          deadline: deadline.value,
          user_id: user_id.value
        }
      : {
          status_id: status_id.value
        }

    await axios.put(`/api/taken/${route.params.id}`, payload)
    router.push('/taken')
  } catch (err) {
    if (err.response?.status === 422) {
      error.value = Object.values(err.response.data.errors).flat().join(', ')
    } else {
      error.value = 'Er ging iets mis bij het opslaan.'
    }
  }
}
</script>
