<template>
  <!-- This shows the page with the tasks that are done, or are in progress are have to begin with  -->
  <div class="pagina">
    <main class="inhoud">
      <div style="padding: 2rem;">
      <h2>Statistiekenpagina</h2>
      <p><strong>To do:</strong> {{ todo }}</p>
      <p><strong>Bezig:</strong> {{ bezig }}</p>
      <p><strong>Afgerond:</strong> {{ afgerond }}</p>
    </div>
    </main>
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

<style scoped>
/* This sets the height and the background-color of the page */ 
.pagina {
  min-height: 100vh;
  background-color: #dcdcdc;
}

/* This sets the background-color and padding for the header */
.header {
  background-color: lime;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* This sets the color and size of the text for the logo */ 
.logo {
  color: red;
  font-size: 2rem;
  margin: 0;
}

/* This sets the font and color of the navigation button */
.navigatie button {
  background: none;
  border: none;
  font-weight: bold;
  color: red;
  cursor: pointer;
}

/* This sets the position of the navigation */
.navigatie {
  display: flex;
  gap: 1rem;
}

/* This makes space between text in a webpage */
.inhoud {
  padding: 2rem;
}
</style>
