<template>
  <div style="background-color: white; min-height: 100vh; padding: 0; margin: 0;">
    
    <!-- HEADER -->
    <!---<header style="background-color: lime; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
      <h1 style="color: red; font-size: 2rem; margin: 0;">Suriname</h1>
      <nav style="display: flex; gap: 1rem;">
        <button @click="logout" style="color: red; background: none; border: none; font-weight: bold;">Uitloggen</button>
        <button @click="$router.push('/statistieken')" style="color: red; background: none; border: none; font-weight: bold;">Statistiekenpagina</button>
        <button @click="$router.push('/taken')" style="color: red; background: none; border: none; font-weight: bold;">Takenlijst</button>
      </nav>
    </header>-->

    <div style="padding: 2 rem;">
    <!-- Toevoegen knop alleen voor admin -->
    <div v-if="authStore.isAdmin" style="margin-bottom: 1rem; text-align: right;">
      <button style="background-color: gold; padding: 0.5rem 1rem; font-weight: bold;" @click="toevoegen">
        Toevoegen
      </button>
    </div>
  </div>

    <!-- MAIN -->
    <main style="padding: 2rem;">
      <h2>Takenlijst</h2>
<!-- FILTERS -->
<div style="margin-bottom: 2rem;">
  <label for="status">Filter op status:</label>
  <select v-model="statusFilter" id="status" style="margin-right: 2rem;">
    <option value="">Alle</option>
    <option value="to do">To do</option>
    <option value="in behandeling">In behandeling</option>
    <option value="voltooid">Voltooid</option>
  </select>

  <label for="deadline">Filter op deadline:</label>
  <select v-model="deadlineFilter" id="deadline">
    <option value="">Alle</option>
    <option value="overdue">Verlopen</option>
    <option value="today">Vandaag</option>
    <option value="upcoming">Komende</option>
  </select>
</div>

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
          <tr v-for="taak in gefilterdeTaken" :key="taak.id" style="border-top: 1px solid black;">
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.title }}</td>
            <td style="padding: 0.5rem; white-space: pre-line;">{{ taak.description }}</td>
            <td style="padding: 0.5rem;">{{ taak.status.name }}</td>
            <td style="padding: 0.5rem;">{{ taak.deadline }}</td>
            <td v-if="gebruiker.role === 'admin'" style="padding: 0.5rem;">{{ taak.user?.name }}</td>

            <td style="padding: 0.5rem; text-align: right;">
              <!-- Admin mag alles -->
              <template v-if="gebruiker && gebruiker.role === 'admin'">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
  <button 
    style="background-color: gold; padding: 0.3rem 1rem; border: 1px solid black; font-weight: bold; min-width: 120px;" 
    @click="verwijderTaak(taak.id)">
    Verwijderen
  </button>
  <button 
    style="background-color: gold; padding: 0.3rem 1rem; border: 1px solid black; font-weight: bold; min-width: 120px;" 
    @click="bewerkTaak(taak.id)">
    Bewerken
  </button>
</div>

</template>


              <!-- Gebruiker mag alleen status wijzigen -->
              <template v-else>
                <button v-if="taak.user_id === gebruiker.id" style="background-color: gold; padding: 0.3rem 1rem;" @click="bewerkTaak(taak.id)">Status wijzigen</button>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from '@/axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore() // ← VOEG TOE
const taken = ref([])
const statusFilter = ref('')
const deadlineFilter = ref('')
const gebruiker = computed(() => authStore.user)


// Haal gebruiker en taken op
onMounted(async () => {
  try {
    //const userRes = await axios.get('/api/user')
    // gebruiker.value = userRes.data

    const takenRes = await axios.get('/api/taken')
    taken.value = takenRes.data
  } catch (err) {
    console.error('Fout bij ophalen data:', err)
  }
})

// Logout
/*const logout = async () => {
  try {
    await axios.post('/api/logout')
    await axios.get('/sanctum/csrf-cookie')
  } catch (err) {
    console.warn('Fout bij uitloggen:', err)
  } finally {
    router.push('/login')
  }
}*/

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

const gefilterdeTaken = computed(() => {
  return taken.value.filter(taak => {
    const statusMatch = statusFilter.value === '' || taak.status.name.toLowerCase() === statusFilter.value.toLowerCase()

    const vandaag = new Date().toISOString().split('T')[0]
    const taakDatum = taak.deadline

    let deadlineMatch = true
    if (deadlineFilter.value === 'overdue') {
      deadlineMatch = taakDatum < vandaag
    } else if (deadlineFilter.value === 'today') {
      deadlineMatch = taakDatum === vandaag
    } else if (deadlineFilter.value === 'upcoming') {
      deadlineMatch = taakDatum > vandaag
    }

    return statusMatch && deadlineMatch
  })
})
</script>
