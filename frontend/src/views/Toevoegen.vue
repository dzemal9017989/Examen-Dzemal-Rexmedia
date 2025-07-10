<template>
  <div class="min-vh-100 bg-white m-0 p-0 d-flex justify-content-center align-items-start">
    <main class="w-100 p-4 d-flex flex-column align-items-center">
      <h2 class="mb-4">Taak aanmaken of bewerken</h2>

      <form @submit.prevent="opslaan" class="bg-warning p-4 rounded shadow" style="width: 100%; max-width: 500px;">
        <!-- Titel -->
        <div class="mb-3">
          <label class="form-label fw-bold">Titel</label>
          <input type="text" class="form-control" placeholder="Vul hier een titel in" v-model="title" required>
        </div>

        <!-- Beschrijving -->
        <div class="mb-3">
          <label class="form-label fw-bold">Beschrijving</label>
          <input type="text" class="form-control" placeholder="Vul hier een beschrijving in" v-model="description" required>
        </div>

        <!-- Status -->
        <div class="mb-3">
          <label class="form-label fw-bold">Status</label>
          <select class="form-select" v-model="status_id" required>
            <option :value="1">Open</option>
            <option :value="2">In behandeling</option>
            <option :value="3">Voltooid</option>
          </select>
        </div>

        <!-- Deadline -->
        <div class="mb-3">
          <label class="form-label fw-bold">Deadline</label>
          <input type="date" class="form-control" :min="today" v-model="deadline" required>
        </div>

        <!-- Toewijzen aan gebruiker (alleen voor admin) -->
        <div v-if="gebruiker.role === 'admin'" class="mb-3">
          <label class="form-label fw-bold">Toewijzen aan gebruiker</label>
          <select class="form-select" v-model="user_id" required>
            <option disabled value="">-- Kies gebruiker --</option>
            <option v-for="g in gebruikers" :key="g.id" :value="g.id">
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
// These imports are for setting the foundations for the other things within the script tags
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from '@/axios'

const today = new Date().toISOString().split('T')[0]


// These const variables are for defining things that derive from the HTML form
const router = useRouter()

const title = ref('')
const description = ref('')
const status_id = ref(1)
const deadline = ref('')
const error = ref('')
const gebruiker = ref({})
const gebruikers = ref([])
const user_id = ref('')

// This function gets the logged in user and if the user is an admin he will get the users by the dropdown menu 
onMounted(async () => {
  try {
    const userRes = await axios.get('/api/user')
    gebruiker.value = userRes.data

    if (gebruiker.value.role === 'admin') {
      const res = await axios.get('/api/users')
      gebruikers.value = res.data
    }
  } catch (err) {
    error.value = 'Kon gebruiker(s) niet ophalen.'
  }
})

// This const function sends the form to the Laravel API
const opslaan = async () => {
  error.value = ''

  try {
    const taak = {
      title: title.value,
      description: description.value,
      status_id: status_id.value,
      deadline: deadline.value
    }

    if (gebruiker.value.role === 'admin') {
      taak.user_id = user_id.value
    }

    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/api/taken', taak)
    router.push('/taken')
  } catch (err) {
    if (err.response && err.response.status === 422) {
      error.value = Object.values(err.response.data.errors).flat().join(', ')
    } else {
      error.value = 'Er ging iets mis bij het opslaan.'
    }
  }
}
</script>

