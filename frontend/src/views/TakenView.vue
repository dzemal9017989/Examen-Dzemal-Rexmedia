<template>
  <div class="bg-white min-vh-100 py-4">
    <div class="container">

      <!-- Toevoegen-knop bovenaan (alleen voor admin) -->
      <div v-if="authStore.isAdmin" class="mb-3 text-end">
        <button class="btn btn-warning fw-bold" @click="toevoegen">
          Toevoegen
        </button>
      </div>

      <!-- Titel en filters -->
      <main>
        <h2 class="mb-4">Takenlijst</h2>


<!-- Filters in één regel -->
<div class="row g-3 mb-4 align-items-end">
  <div class="col-md-4">
    <label for="sort" class="form-label">Sorteer op:</label>
    <select v-model="sortOrder" id="sort" class="form-select">
      <option value="">Standaard</option>
      <option value="title-asc">Titel A-Z</option>
      <option value="title-desc">Titel Z-A</option>
      <option value="deadline-asc">Deadline oplopend</option>
      <option value="deadline-desc">Deadline aflopend</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="status" class="form-label">Filter op status:</label>
    <select v-model="statusFilter" id="status" class="form-select">
      <option value="">Alle</option>
      <option value="to do">To do</option>
      <option value="in behandeling">In behandeling</option>
      <option value="voltooid">Voltooid</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="deadline" class="form-label">Filter op deadline:</label>
    <select v-model="deadlineFilter" id="deadline" class="form-select">
      <option value="">Alle</option>
      <option value="overdue">Verlopen</option>
      <option value="today">Vandaag</option>
      <option value="upcoming">Komende</option>
    </select>
  </div>
</div>


        <!-- Takenlijst-tabel -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Titel</th>
                <th>Beschrijving</th>
                <th>Status</th>
                <th>Deadline</th>
                <th v-if="gebruiker.role === 'admin'">Gebruiker</th>
                <th class="text-end">Acties</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="taak in gefilterdeTaken" :key="taak.id">
                <td>{{ taak.title }}</td>
                <td>{{ taak.description }}</td>
                <td>{{ taak.status.name }}</td>
                <td>{{ taak.deadline }}</td>
                <td v-if="gebruiker.role === 'admin'">{{ taak.user?.name }}</td>
                <td class="text-end">
                  <!-- Acties voor admin -->
                  <template v-if="gebruiker && gebruiker.role === 'admin'">
                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                      <button class="btn btn-danger btn-sm" @click="verwijderTaak(taak.id)">
                        Verwijderen
                      </button>
                      <button class="btn btn-secondary btn-sm" @click="bewerkTaak(taak.id)">
                        Bewerken
                      </button>
                    </div>
                  </template>

                  <!-- Actie voor gebruiker (status wijzigen) -->
                  <template v-else>
                    <button
                      v-if="taak.user_id === gebruiker.id"
                      class="btn btn-warning btn-sm"
                      @click="bewerkTaak(taak.id)"
                    >
                      Status wijzigen
                    </button>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Toevoegen-knop onderaan (alleen voor admin) -->
        <div v-if="authStore.isAdmin" class="text-end mt-3">
          <button class="btn btn-warning fw-bold" @click="toevoegen">
            Toevoegen
          </button>
        </div>
      </main>
    </div>
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

const sortOrder = ref('')



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
  // Eerst filteren
  let resultaten = taken.value.filter(taak => {
    const statusMatch =
      statusFilter.value === '' ||
      taak.status.name.toLowerCase() === statusFilter.value.toLowerCase()

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

  // Dan sorteren
  if (sortOrder.value === 'title-asc') {
    resultaten.sort((a, b) => a.title.localeCompare(b.title))
  } else if (sortOrder.value === 'title-desc') {
    resultaten.sort((a, b) => b.title.localeCompare(a.title))
  } else if (sortOrder.value === 'deadline-asc') {
    resultaten.sort((a, b) => a.deadline.localeCompare(b.deadline))
  } else if (sortOrder.value === 'deadline-desc') {
    resultaten.sort((a, b) => b.deadline.localeCompare(a.deadline))
  }

  return resultaten
})

</script>
