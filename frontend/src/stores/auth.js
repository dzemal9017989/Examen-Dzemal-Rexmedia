// These importis are important for the authentication store to work correctly
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/axios'

// This is the name of the store that you can use anywhere in your application
export const useAuthStore = defineStore('auth', () => {
  const user = ref(null) // The current user object, initially null if not authenticated
  const loading = ref(false) // This shows if the authentication check is in progress

  // This const returns true if the user is authenticated and false if not
  const isAuthenticated = computed(() => !!user.value) 
  // This const returns true if the user is an admin and false if not
  const isAdmin = computed(() => user.value?.role === 'admin')

  // This const function checks if the user is logged in which helps by reloading the page
  const checkAuth = async () => {
    if (loading.value) return
    loading.value = true
    try {
      const response = await axios.get('/api/user')
      user.value = response.data
      return true
    } catch (error) {
      user.value = null
      return false
    } finally {
      loading.value = false
    }
  }

  // This const function checks if the user is logged in and if yes is saves the user data
  const login = async (credentials) => {
    loading.value = true
    try {
      await axios.get('/sanctum/csrf-cookie')
      const response = await axios.post('/api/login', credentials)
      user.value = response.data.user
      return { success: true }
    } catch (error) {
      user.value = null
      return { success: false, message: 'Login mislukt' }
    } finally {
      loading.value = false
    }
  }

  // This const function logs the user out and clears the user data
  const logout = async () => {
    try {
      await axios.post('/api/logout')
    } catch (error) {
      console.warn('Logout error:', error)
    } finally {
      user.value = null
    }
  }

  // These data and functions are returned so they can be used in components
  return { user, loading, isAuthenticated, isAdmin, checkAuth, login, logout }
})