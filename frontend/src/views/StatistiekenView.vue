<template>
  <div class="min-vh-100 bg-light">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8 bg-white p-5 rounded shadow">
          <h2 class="mb-4">Statistiekenpagina</h2>
          <p><strong>To do:</strong> {{ todo }}</p>
          <p><strong>Bezig:</strong> {{ bezig }}</p>
          <p><strong>Afgerond:</strong> {{ afgerond }}</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
// These imports are essential for the functioning of the webpage
import { ref, onMounted } from 'vue'
// import { useRouter } from 'vue-router'
import axios from '@/axios'

// const router = useRouter()

// These const variables have a value that is assigned to zero and will increase when a task is set to the things within the HTML tags
const todo = ref(0)
const bezig = ref(0)
const afgerond = ref(0)

/*
const logout = async () => {
  try {
    await axios.post('/api/logout')
    await axios.get('/sanctum/csrf-cookie') // Optioneel, voor zekerheid
    router.push('/login') // ✅ Terug naar loginformulier via router
  } catch (err) {
    console.error('Fout bij uitloggen:', err)
  }
}
*/

// This function runs when the page is loaded and fetches all tasks from the API
onMounted(async () => {
  try {
    const { data } = await axios.get('/api/taken')
    todo.value = data.filter(t => t.status_id === 1).length
    bezig.value = data.filter(t => t.status_id === 2).length
    afgerond.value = data.filter(t => t.status_id === 3).length
  } catch (err) {
    console.error('Fout bij ophalen taken:', err)
  }
})
</script>

