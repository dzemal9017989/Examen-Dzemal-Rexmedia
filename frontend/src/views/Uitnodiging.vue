<template>
  <div style="min-height: 100vh; background-color: white; display: flex; justify-content: center; align-items: center;">
    
    <!-- Loading -->
    <div v-if="laden" style="background-color: #e8be17; padding: 2rem; width: 400px; text-align: center;">
      <h2>Laden...</h2>
    </div>

    <!-- Ongeldige uitnodiging -->
    <div v-else-if="!uitnodiging" style="background-color: #e8be17; padding: 2rem; width: 400px; text-align: center;">
      <h2 style="color: red;">Ongeldige uitnodiging</h2>
      <p>Deze uitnodiging is verlopen of al gebruikt.</p>
      <button @click="$router.push('/login')" style="background-color: red; color: white; padding: 0.5rem 1rem;">
        Naar login
      </button>
    </div>

    <!-- Account succesvol aangemaakt -->
    <div v-else-if="klaar" style="background-color: #e8be17; padding: 2rem; width: 400px; text-align: center;">
      <h2 style="color: green;">Account aangemaakt!</h2>
      <p>Je kunt nu inloggen met je email en wachtwoord.</p>
      <button @click="$router.push('/login')" style="background-color: red; color: white; padding: 0.5rem 1rem;">
        Inloggen
      </button>
    </div>

    <!-- Wachtwoord instellen -->
    <div v-else style="background-color: #e8be17; padding: 2rem; width: 400px;">
      <h2>Account activeren</h2>
      <p><strong>Welkom {{ uitnodiging.name }}!</strong></p>
      <p>Stel hieronder je wachtwoord in:</p>

      <form @submit.prevent="activeren">
        <label style="font-weight: bold;">Email</label>
        <input :value="uitnodiging.email" disabled style="width: 100%; margin-bottom: 1rem; background-color: #f0f0f0;">

        <label style="font-weight: bold;">Wachtwoord</label>
        <input v-model="wachtwoord" type="password" placeholder="Minimaal 8 tekens" style="width: 100%; margin-bottom: 1rem;">

        <label style="font-weight: bold;">Bevestig wachtwoord</label>
        <input v-model="bevestiging" type="password" placeholder="Herhaal je wachtwoord" style="width: 100%; margin-bottom: 1rem;">

        <div v-if="fout" style="color: red; margin-bottom: 1rem;">{{ fout }}</div>

        <button type="submit" style="background-color: red; color: white; padding: 0.5rem 1rem; width: 100%;">
          Account activeren
        </button>
      </form>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/axios'

const route = useRoute()

const laden = ref(true)
const uitnodiging = ref(null)
const klaar = ref(false)

const wachtwoord = ref('')
const bevestiging = ref('')
const fout = ref('')

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

onMounted(() => {
  ladenUitnodiging()
})
</script>