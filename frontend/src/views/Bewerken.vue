<template>
  <div class="min-vh-100 bg-white m-0 p-0 d-flex justify-content-center align-items-start">
    <main class="w-100 p-4 d-flex flex-column align-items-center">
      <h2 class="mb-4">Taak bewerken</h2>

      <form @submit.prevent="opslaan" class="bg-warning p-4 rounded shadow" style="width: 100%; max-width: 500px;">
        <!-- Alleen admin mag titel en beschrijving bewerken -->
        <div v-if="user.role === 'admin'">
          <div class="mb-3">
            <label class="form-label fw-bold">Titel</label>
            <input type="text" v-model="title" class="form-control" placeholder="Titel" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Beschrijving</label>
            <input type="text" v-model="description" class="form-control" placeholder="Beschrijving" required>
          </div>
        </div>

        <!-- Status (iedereen mag wijzigen) -->
        <div class="mb-3">
          <label class="form-label fw-bold">Status</label>
          <select v-model="status_id" class="form-select" required>
            <option :value="1">Open</option>
            <option :value="2">In behandeling</option>
            <option :value="3">Voltooid</option>
          </select>
        </div>

        <!-- Deadline -->
        <div class="mb-3">
          <label class="form-label fw-bold">Deadline</label>
          <input type="date" :min="today" v-model="deadline" class="form-control" required>
        </div>

        <!-- Alleen admin mag toewijzen -->
        <div v-if="user.role === 'admin'" class="mb-3">
          <label class="form-label fw-bold">Toewijzen aan gebruiker</label>
          <select v-model="user_id" class="form-select" required>
            <option disabled value="">-- Kies gebruiker --</option>
            <option v-for="g in users" :key="g.id" :value="g.id">
              {{ g.name }}
            </option>
          </select>
        </div>

        <!-- Foutmelding -->
        <div v-if="error" class="text-danger fw-bold mb-3">
          {{ error }}
        </div>

        <!-- Opslaan knop -->
        <div class="text-center">
          <button type="submit" class="btn btn-danger fw-bold px-4">
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
