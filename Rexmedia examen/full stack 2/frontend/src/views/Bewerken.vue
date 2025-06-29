<template>
  <div style="min-height: 100vh; background-color: white; margin: 0; padding: 0;">
    <!-- header blijft gelijk -->

    <main style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 2rem;">
      <h2>Taak bewerken</h2>
      <form @submit.prevent="opslaan" style="background-color: #f1c40f; padding: 2rem; width: 400px;">
        <label style="font-weight: bold;">Titel</label>
        <input type="text" v-model="titel" placeholder="Titel" style="width: 100%; margin-bottom: 1rem;" />

        <label style="font-weight: bold;">Beschrijving</label>
        <input type="text" v-model="beschrijving" placeholder="Beschrijving" style="width: 100%; margin-bottom: 1rem;" />

        <label style="font-weight: bold;">Status</label>
        <select v-model="status_id" style="width: 100%; margin-bottom: 1rem;">
          <option :value="1">Open</option>
          <option :value="2">In behandeling</option>
          <option :value="3">Voltooid</option>
        </select>

        <label style="font-weight: bold;">Deadline</label>
        <input type="date" v-model="deadline" style="width: 100%; margin-bottom: 1rem;" />

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

const route = useRoute()
const router = useRouter()

const titel = ref('')
const beschrijving = ref('')
const status_id = ref(1)
const deadline = ref('')
const error = ref('')

// ✅ haal bestaande taak op
onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/taken/${route.params.id}`)
    titel.value = data.titel
    beschrijving.value = data.beschrijving
    status_id.value = data.status_id
    deadline.value = data.deadline
  } catch (err) {
    error.value = 'Kan taak niet ophalen.'
  }
})

const opslaan = async () => {
  error.value = ''
  try {
    await axios.put(`/api/taken/${route.params.id}`, {
      titel: titel.value,
      beschrijving: beschrijving.value,
      status_id: status_id.value,
      deadline: deadline.value
    })
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
