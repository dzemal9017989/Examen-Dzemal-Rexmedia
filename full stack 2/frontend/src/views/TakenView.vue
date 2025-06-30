<template>
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

    <!-- Toevoegen knop alleen voor admin -->
    <div v-if="gebruiker.role === 'admin'" style="margin: 1rem; text-align: right;">
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
            <th v-if="gebruiker.role === 'admin'" style="width: 20%; text-align: left; padding: 0.5rem;">Gebruiker</th>
            <th style="text-align: right; padding: 0.5rem;">Acties</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="taak in taken" :key="taak.id" style="border-top: 1px solid black;">
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.titel }}</td>
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.beschrijving }}</td>
            <td style="padding: 0.5rem;">{{ taak.status.naam }}</td>
            <td style="padding: 0.5rem;">{{ taak.deadline }}</td>
            <td v-if="gebruiker.role === 'admin'" style="padding: 0.5rem;">{{ taak.gebruiker?.name }}</td>

            <td style="padding: 0.5rem; text-align: right;">
              <!-- Admin mag alles -->
              <template v-if="gebruiker.role === 'admin'">
                <button style="background-color: gold; padding: 0.3rem 1rem; margin-right: 0.5rem;" @click="verwijderTaak(taak.id)">Verwijderen</button>
                <button style="background-color: gold; padding: 0.3rem 1rem;" @click="bewerkTaak(taak.id)">Bewerken</button>
              </template>

              <!-- Gebruiker mag alleen status wijzigen -->
              <template v-else>
                <button v-if="taak.gebruiker_id === gebruiker.id" style="background-color: gold; padding: 0.3rem 1rem;" @click="bewerkTaak(taak.id)">Status wijzigen</button>
              </template>
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
const gebruiker = ref({})

// Haal gebruiker en taken op
onMounted(async () => {
  try {
    const userRes = await axios.get('/api/user')
    gebruiker.value = userRes.data

    const takenRes = await axios.get('/api/taken')
    taken.value = takenRes.data
  } catch (err) {
    console.error('Fout bij ophalen data:', err)
  }
})

// Logout
const logout = async () => {
  try {
    await axios.post('/api/logout')
    await axios.get('/sanctum/csrf-cookie')
  } catch (err) {
    console.warn('Fout bij uitloggen:', err)
  } finally {
    router.push('/login')
  }
}

// Verwijder een taak
const verwijderTaak = async (id) => {
  if (!confirm('Weet je zeker dat je deze taak wilt verwijderen?')) return

  try {
    await axios.delete(`/api/taken/${id}`)
    taken.value = taken.value.filter(t => t.id !== id)
  } catch (err) {
    console.error('Fout bij verwijderen:', err)
  }
}

// Bewerken
const bewerkTaak = (id) => {
  router.push(`/bewerken/${id}`)
}

// Toevoegen
const toevoegen = () => {
  router.push('/toevoegen')
}
</script>
