<template>
  <div class="container-fluid bg-white min-vh-100 py-4">
    <!-- Bovenste knop -->
    <div class="d-flex justify-content-end px-4 mb-3">
      <button class="btn btn-warning fw-bold" @click="showForm = !showForm">
        {{ showForm ? 'Annuleren' : 'Gebruiker Uitnodigen' }}
      </button>
    </div>

    <main class="container">
      <!-- Titel -->
      <h2 class="text-center mb-4">Gebruikersbeheer</h2>

      <!-- Formulier -->
      <div class="row justify-content-center mb-5">
        <div v-if="showForm" class="col-md-6 col-lg-5">
          <div class="card bg-warning p-4">
            <h4 class="mb-3">Nieuwe Gebruiker Uitnodigen</h4>
            <form @submit.prevent="sendInvitation">
              <div class="mb-3">
                <label class="form-label fw-bold">Naam</label>
                <input v-model="form.name" type="text" class="form-control" placeholder="Vul hier de naam in" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input v-model="form.email" type="email" class="form-control" placeholder="Vul hier het emailadres in" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Rol</label>
                <select v-model="form.role" class="form-select" required>
                  <option value="user">Gebruiker</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div v-if="error" class="text-danger fw-bold mb-2">{{ error }}</div>
              <div v-if="success" class="text-success fw-bold mb-2">{{ success }}</div>
              <button type="submit" class="btn btn-danger btn-sm">Uitnodiging Versturen</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Actieve gebruikers -->
      <h4>Actieve Gebruikers</h4>
      <div class="table-responsive mb-5">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Naam</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Sinds</th>
              <th class="text-end">Acties</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>
                <span :class="user.role === 'admin' ? 'text-danger fw-bold' : 'text-primary fw-bold'">
                  {{ user.role === 'admin' ? 'Admin' : 'Gebruiker' }}
                </span>
              </td>
              <td>{{ formatDate(user.created_at) }}</td>
              <td class="text-end">
                <button
                  @click="deleteUser(user.id)"
                  class="btn btn-danger btn-sm"
                  :disabled="user.role === 'admin'"
                >
                  Verwijderen
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Openstaande uitnodigingen -->
      <h4>Openstaande Uitnodigingen</h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Naam</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Verloopt</th>
              <th class="text-end">Acties</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invitation in invitations" :key="invitation.id">
              <td>{{ invitation.name }}</td>
              <td>{{ invitation.email }}</td>
              <td>
                <span :class="invitation.role === 'admin' ? 'text-danger fw-bold' : 'text-primary fw-bold'">
                  {{ invitation.role === 'admin' ? 'Admin' : 'Gebruiker' }}
                </span>
              </td>
              <td>{{ formatDate(invitation.expires_at) }}</td>
              <td class="text-end">
                <button @click="cancelInvitation(invitation.id)" class="btn btn-warning btn-sm">
                  Intrekken
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="invitations.length === 0" class="text-center text-muted fst-italic mt-3">
          Geen openstaande uitnodigingen
        </div>
      </div>
    </main>
  </div>
</template>

  
  <script setup>
  // These imports are for setting the foundations for the other things within the script tags
  import { ref, onMounted } from 'vue'
  import axios from '@/axios'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  // Data that is useful for the getting the invitations 
  const users = ref([])
  const invitations = ref([])
  const showForm = ref(false)
  // Data that is used for the form
  const form = ref({
    name: '',
    email: '',
    role: 'user'
  })
  
  // Consts that help to handle errors for example
  const error = ref('')
  const success = ref('')
  
  // Functions that help to get invitations and users at the same time
  const loadData = async () => {
    try {
      const [usersRes, invitationsRes] = await Promise.all([
        axios.get('/api/admin/users'),
        axios.get('/api/admin/invitations')
      ])
      
      users.value = usersRes.data
      invitations.value = invitationsRes.data
    } catch (err) {
      console.error('Fout bij laden data:', err)
    }
  }
  
  // This function sends invitations to new users
  const sendInvitation = async () => {
    error.value = ''
    success.value = ''
    
    try {
      await axios.post('/api/admin/invitations', form.value)
      success.value = `Uitnodiging verstuurd naar ${form.value.email}`
      
      // Reset form
      form.value = { name: '', email: '', role: 'user' }
      showForm.value = false
      
      // Reload data
      await loadData()
      
      // Clear success message after 3 seconds
      setTimeout(() => { success.value = '' }, 3000)
      
    } catch (err) {
      if (err.response?.status === 422) {
        error.value = Object.values(err.response.data.errors).flat().join(', ')
      } else {
        error.value = 'Fout bij versturen uitnodiging'
      }
    }
  }
  
  // This function cancels invitations
  const cancelInvitation = async (id) => {
    if (!confirm('Weet je zeker dat je deze uitnodiging wilt intrekken?')) return
    
    try {
      await axios.delete(`/api/admin/invitations/${id}`)
      await loadData()
    } catch (err) {
      console.error('Fout bij intrekken uitnodiging:', err)
      error.value = 'Fout bij intrekken uitnodiging'
    }
  }
  
  // This function returns a readable date
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('nl-NL')
  }
  

  // Load data on mount
  onMounted(loadData)

  // This function deletes a user
  const deleteUser = async (id) => {
  if (!confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')) return

  try {
    await axios.delete(`/api/admin/users/${id}`)
    await loadData()
  } catch (err) {
    console.error('Fout bij verwijderen gebruiker:', err)
    error.value = 'Fout bij verwijderen gebruiker'
  }
}

  </script>