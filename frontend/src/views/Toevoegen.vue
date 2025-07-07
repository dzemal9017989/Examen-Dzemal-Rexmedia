<template>
  <div style="min-height: 100vh; background-color: white; margin: 0; padding: 0;">
    

    <!-- Formu -->
    <main style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 2rem;">
      <h2>Taak aanmaken of bewerken</h2>
      <form @submit.prevent="opslaan" style="background-color: #f1c40f; padding: 2rem; width: 400px;">
        <label style="font-weight: bold;">Titel</label>
        <input type="text" placeholder="Vul hier een titel in" v-model="title"
               style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <label style="font-weight: bold;">Beschrijving</label>
        <input type="text" placeholder="Vul hier een beschrijving in" v-model="description"
               style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <label style="font-weight: bold;">Status</label>
        <select v-model="status_id" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">
          <option :value="1">Open</option>
          <option :value="2">In behandeling</option>
          <option :value="3">Voltooid</option>
        </select>

        <label style="font-weight: bold;">Deadline</label>
        <input type="date" v-model="deadline" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

        <label v-if="gebruiker.role === 'admin'" style="font-weight: bold;">Toewijzen aan gebruiker</label>
        <select v-if="gebruiker.role === 'admin'" v-model="user_id" style="width: 100%; margin-bottom: 1rem;">
        <option disabled value="">-- Kies gebruiker --</option>
        <option v-for="g in gebruikers" :key="g.id" :value="g.id">
        {{ g.name }}
        </option>
        </select>


        <!-- It only shows this if an error occurs -->
<div v-if="error" style="color: red; font-weight: bold; margin-bottom: 1rem;">
  {{ error }}
</div>

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
// These imports are for setting the foundations for the other things within the script tags
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from '@/axios'

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

