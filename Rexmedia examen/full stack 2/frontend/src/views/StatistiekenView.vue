<template>
  <div class="pagina">
    <header class="header">
      <h1 class="logo">Suriname</h1>
      <nav class="navigatie">
        <button @click="logout">Uitloggen</button>
        <button @click="$router.push('/statistieken')">Statistiekenpagina</button>
        <button @click="$router.push('/taken')">Takenlijst</button>
      </nav>
    </header>

    <main class="inhoud">
      <h2>Statistiekenpagina</h2>
      <p><strong>To do:</strong> {{ todo }}</p>
      <p><strong>Bezig:</strong> {{ bezig }}</p>
      <p><strong>Afgerond:</strong> {{ afgerond }}</p>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'

const todo = ref(0)
const bezig = ref(0)
const afgerond = ref(0)

const logout = async () => {
  try {
    await axios.post('/api/logout')
    window.location.href = '/login'
  } catch (err) {
    console.error('Fout bij uitloggen:', err)
  }
}

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
.pagina {
  min-height: 100vh;
  background-color: #dcdcdc;
}

.header {
  background-color: lime;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  color: red;
  font-size: 2rem;
  margin: 0;
}

.navigatie button {
  background: none;
  border: none;
  font-weight: bold;
  color: red;
  cursor: pointer;
}

.navigatie {
  display: flex;
  gap: 1rem;
}

.inhoud {
  padding: 2rem;
}
</style>
