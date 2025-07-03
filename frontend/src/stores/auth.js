import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const loading = ref(false)

  const isAuthenticated = computed(() => !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

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

  const logout = async () => {
    try {
      await axios.post('/api/logout')
    } catch (error) {
      console.warn('Logout error:', error)
    } finally {
      user.value = null
    }
  }

  return { user, loading, isAuthenticated, isAdmin, checkAuth, login, logout }
})