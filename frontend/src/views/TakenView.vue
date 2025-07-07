<template>
  <div style="background-color: white; min-height: 100vh; padding: 0; margin: 0;">
    <div style="padding: 2 rem;">
    <!-- The button is only visible for the admin -->
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

<!--- This is what you see in the tasks page -->
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


              <!-- Only the user can change the state of a task -->
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
// These imports are for setting the foundations for the other things within the script tags
import { ref, onMounted, computed } from 'vue'
import axios from '@/axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// These const variables are important for the things that change in a page
const router = useRouter()
const authStore = useAuthStore()
const taken = ref([])
const statusFilter = ref('')
const deadlineFilter = ref('')
const gebruiker = computed(() => authStore.user)


// Get users and tasks 
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


// This const function deletes a task
const verwijderTaak = async (id) => {
  if (!confirm('Weet je zeker dat je deze taak wilt verwijderen?')) return

  try {
    await axios.delete(`/api/taken/${id}`)
    taken.value = taken.value.filter(t => t.id !== id)
  } catch (err) {
    console.error('Fout bij verwijderen:', err)
  }
}

// This brings you to a page where you can edit a task by a form
const bewerkTaak = (id) => {
  router.push(`/bewerken/${id}`)
}

// This brings you to the page to add a task through a form
const toevoegen = () => {
  router.push('/toevoegen')
}

// This function filters for status and deadline so that you can click on a button in a dropdown to see a certain thing in the page
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
