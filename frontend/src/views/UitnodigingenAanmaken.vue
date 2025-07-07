<template>
    <div style="background-color: white; min-height: 100vh; padding: 0; margin: 0;">
  
      <!-- This is a button to make or to cancel to make a invitation  -->
      <div style="margin: 1rem; text-align: right;">
        <button style="background-color: gold; padding: 0.5rem 1rem; font-weight: bold;" @click="showForm = !showForm">
          {{ showForm ? 'Annuleren' : 'Gebruiker Uitnodigen' }}
        </button>
      </div>
  
      <main style="padding: 2rem;">

<div style="text-align: center; margin-bottom: 1rem;">
  <h2 style="margin: 0;">Gebruikersbeheer</h2>
</div>

<!----- Form -->
<div style="display: flex; justify-content: center; margin-bottom: 2rem;">
  <div v-if="showForm" style="background-color: #f1c40f; padding: 2rem; width: 400px;">
    <h3 style="margin-top: 0;">Nieuwe Gebruiker Uitnodigen</h3>
    <form @submit.prevent="sendInvitation">
      
      <label style="font-weight: bold;">Naam</label>
      <input v-model="form.name" type="text" placeholder="Vul hier de naam in" required
             style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

      <label style="font-weight: bold;">Email</label>
      <input v-model="form.email" type="email" placeholder="Vul hier het emailadres in" required
             style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">

      <label style="font-weight: bold;">Rol</label>
      <select v-model="form.role" style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">
        <option value="user">Gebruiker</option>
        <option value="admin">Admin</option>
      </select>

      <div v-if="error" style="color: red; font-weight: bold; margin-bottom: 1rem;">{{ error }}</div>
      <div v-if="success" style="color: green; font-weight: bold; margin-bottom: 1rem;">{{ success }}</div>

      <button type="submit" style="background-color: red; color: white; padding: 0.5rem 1.5rem; border: none; font-weight: bold;">
        Uitnodiging Versturen
      </button>
    </form>
  </div>
</div>


  
        <!-- A table that shows active users -->
        <h3>Actieve Gebruikers</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 3rem;">
          <thead>
            <tr>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Naam</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Email</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Rol</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Sinds</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" style="border-top: 1px solid black;">
  <td style="padding: 0.5rem;">{{ user.name }}</td>
  <td style="padding: 0.5rem;">{{ user.email }}</td>
  <td style="padding: 0.5rem;">
    <span :style="{ color: user.role === 'admin' ? 'red' : 'blue', fontWeight: 'bold' }">
      {{ user.role === 'admin' ? 'Admin' : 'Gebruiker' }}
    </span>
  </td>
  <td style="padding: 0.5rem;">{{ formatDate(user.created_at) }}</td>
  <td style="padding: 0.5rem; text-align: right;">
    <button 
      @click="deleteUser(user.id)" 
      style="background-color: red; color: white; padding: 0.3rem 1rem;"
      :disabled="user.role === 'admin'">
      Verwijderen
    </button>
  </td>
</tr>

          </tbody>
        </table>
  
        <!-- A table that shows invitations that are open to acceot for the admin  -->
        <h3>Openstaande Uitnodigingen</h3>
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Naam</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Email</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Rol</th>
              <th style="text-align: left; padding: 0.5rem; border-bottom: 2px solid black;">Verloopt</th>
              <th style="text-align: right; padding: 0.5rem; border-bottom: 2px solid black;">Acties</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invitation in invitations" :key="invitation.id" style="border-top: 1px solid black;">
              <td style="padding: 0.5rem;">{{ invitation.name }}</td>
              <td style="padding: 0.5rem;">{{ invitation.email }}</td>
              <td style="padding: 0.5rem;">
                <span :style="{ color: invitation.role === 'admin' ? 'red' : 'blue', fontWeight: 'bold' }">
                  {{ invitation.role === 'admin' ? 'Admin' : 'Gebruiker' }}
                </span>
              </td>
              <td style="padding: 0.5rem;">{{ formatDate(invitation.expires_at) }}</td>
              <td style="padding: 0.5rem; text-align: right;">
                <button @click="cancelInvitation(invitation.id)" 
                        style="background-color: gold; padding: 0.3rem 1rem;">
                  Intrekken
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        
        <div v-if="invitations.length === 0" style="padding: 1rem; text-align: center; color: #666; font-style: italic;">
          Geen openstaande uitnodigingen
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