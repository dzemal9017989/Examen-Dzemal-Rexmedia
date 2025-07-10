<template>
  <div class="min-vh-100 bg-white d-flex justify-content-center align-items-center">

    <!-- Laden -->
    <div v-if="laden" class="bg-warning p-4 rounded text-center shadow" style="width: 100%; max-width: 400px;">
      <h2>Laden...</h2>
    </div>

    <!-- Ongeldige uitnodiging -->
    <div v-else-if="!uitnodiging" class="bg-warning p-4 rounded text-center shadow" style="width: 100%; max-width: 400px;">
      <h2 class="text-danger">Ongeldige uitnodiging</h2>
      <p>Deze uitnodiging is verlopen of al gebruikt.</p>
      <button @click="$router.push('/login')" class="btn btn-danger">
        Naar login
      </button>
    </div>

    <!-- Succesvol geactiveerd -->
    <div v-else-if="klaar" class="bg-warning p-4 rounded text-center shadow" style="width: 100%; max-width: 400px;">
      <h2 class="text-success">Account aangemaakt!</h2>
      <p>Je kunt nu inloggen met je email en wachtwoord.</p>
      <button @click="$router.push('/login')" class="btn btn-danger">
        Inloggen
      </button>
    </div>

    <!-- Formulier om wachtwoord in te stellen -->
    <div v-else class="bg-warning p-4 rounded shadow" style="width: 100%; max-width: 400px;">
      <h2 class="mb-3">Account activeren</h2>
      <p><strong>Welkom {{ uitnodiging.name }}!</strong></p>
      <p>Stel hieronder je wachtwoord in:</p>

      <form @submit.prevent="activeren">
        <div class="mb-3">
          <label class="form-label fw-bold">Email</label>
          <input :value="uitnodiging.email" class="form-control" disabled>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Wachtwoord</label>
          <input v-model="wachtwoord" type="password" class="form-control" placeholder="Minimaal 8 tekens" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Bevestig wachtwoord</label>
          <input v-model="bevestiging" type="password" class="form-control" placeholder="Herhaal je wachtwoord" required>
        </div>

        <div v-if="fout" class="text-danger mb-3">{{ fout }}</div>

        <button type="submit" class="btn btn-danger w-100">
          Account activeren
        </button>
      </form>
    </div>

  </div>
</template>


<script setup>
// These imports are for setting the foundations for the other things within the script tags
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/axios'

// These const variables are for the functiong of the page
const route = useRoute()

const laden = ref(true)
const uitnodiging = ref(null)
const klaar = ref(false)

// These const variables are for the form
const wachtwoord = ref('')
const bevestiging = ref('')
const fout = ref('')

// This function gets the invitation based on the token in the URL
const ladenUitnodiging = async () => {
  try {
    const token = route.params.token
    const response = await axios.get(`/api/invitations/${token}`)
    uitnodiging.value = response.data
  } catch (err) {
    console.log('Uitnodiging niet gevonden', err)
    uitnodiging.value = null
  }
  laden.value = false
}

// This function validates the user when he or she clicks on 'Account activeren'
const activeren = async () => {
  fout.value = ''
  
  if (!wachtwoord.value || !bevestiging.value) {
    fout.value = 'Vul alle velden in'
    return
  }
  
  if (wachtwoord.value.length < 8) {
    fout.value = 'Wachtwoord moet minimaal 8 tekens zijn'
    return
  }
  
  if (wachtwoord.value !== bevestiging.value) {
    fout.value = 'Wachtwoorden komen niet overeen'
    return
  }

  try {
    const token = route.params.token
    await axios.post(`/api/invitations/${token}/accept`, {
      password: wachtwoord.value,
      password_confirmation: bevestiging.value
    })
    
    klaar.value = true
    
  } catch (err) {
    if (err.response?.data?.errors) {
      fout.value = Object.values(err.response.data.errors).flat().join(', ')
    } else {
      fout.value = 'Er ging iets mis bij het activeren'
    }
  }
}

// This function gets the invitation when the page is loaded
onMounted(() => {
  ladenUitnodiging()
})
</script>