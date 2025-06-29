<template>
  <!-- Hele pagina wit, zwarte achtergrond verwijderd -->
  <div style="background-color: white; min-height: 100vh; padding: 0; margin: 0;">
    
    <!-- HEADER -->
    <header style="background-color: lime; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
      <h1 style="color: red; font-size: 2rem; margin: 0;">Suriname</h1>
      <nav style="display: flex; gap: 1rem;">
        <button @click="logout" style="color: red; background: none; border: none; font-weight: bold;">Uitloggen</button>
        <button @click="$router.push('/statistieken')" style="color: red; background: none; border: none; font-weight: bold;">Statistiekenpagina</button>
        <button @click="$router.push('/taken')" style="color: red; background: none; border: none; font-weight: bold;">Takenlijst</button>
      </nav>
    </header>

    <!-- Toevoegen knop ONDER de navigatiebalk, vóór de takenlijst -->
    <div style="margin: 1rem; text-align: right;">
      <button style="background-color: gold; padding: 0.5rem 1rem; font-weight: bold;" @click="toevoegen">Toevoegen</button>
    </div>

    <!-- MAIN -->
    <main style="padding: 2rem;">
      <h2>Takenlijst</h2>
      <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
<thead>
  <tr>
    <th style="width: 15%; text-align: left; padding: 0.5rem;">Titel</th>
    <th style="width: 30%; text-align: left; padding: 0.5rem;">Beschrijving</th>
    <th style="width: 15%; text-align: left; padding: 0.5rem;">Status</th>
    <th style="width: 15%; text-align: left; padding: 0.5rem;">Deadline</th>
  </tr>
</thead>
        <tbody>
          <tr v-for="taak in taken" :key="taak.id" style="border-top: 1px solid black;">
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.titel }}</td>
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.beschrijving }}</td>
            <td style="padding: 0.5rem;">{{ taak.status.naam }}</td>
            <td style="padding: 0.5rem;">{{ taak.deadline }}</td>
            <td style="padding: 0.5rem; text-align: right;">
  <button style="background-color: gold; padding: 0.3rem 1rem; margin-right: 0.5rem;" @click="verwijderTaak(taak.id)">Verwijderen</button>
  <button style="background-color: gold; padding: 0.3rem 1rem;" @click="bewerkTaak(taak.id)">Bewerken</button>
</td>

          </tr>
        </tbody>
      </table>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const taken = ref([])

onMounted(async () => {
  const response = await axios.get('/api/taken')
  taken.value = response.data
})


const logout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    console.warn('Fout bij uitloggen:', err.response?.data || err.message)
  } finally {
    router.push('/login')
  }
}


const verwijderTaak = async (id) => {
  if (!confirm('Weet je zeker dat je deze taak wilt verwijderen?')) return;

  try {
    await axios.delete(`/api/taken/${id}`);
    taken.value = taken.value.filter(t => t.id !== id); // taak uit lijst halen
  } catch (err) {
    console.error('Fout bij verwijderen:', err);
  }
};


const bewerkTaak = (id) => {
  router.push(`/bewerken/${id}`)
}

const toevoegen = () => {
  router.push('/toevoegen')
}
</script>
