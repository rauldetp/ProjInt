import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') ?? null)

  const isLoggedIn = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isCoordinateur = computed(() => user.value?.role === 'coordinateur')
  const entrepriseSlug = computed(() => user.value?.coordinateur?.entreprise?.slug ?? null)
  const entrepriseBrand = computed(() => user.value?.coordinateur?.entreprise ?? null)

  async function login(email, password) {
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email, password }),
    })
    if (!res.ok) throw new Error('Identifiants incorrects')
    const data = await res.json()
    token.value = data.token
    localStorage.setItem('token', data.token)
    await fetchMe()
  }

  async function fetchMe() {
    if (!token.value) return
    try {
      const res = await fetch('/api/me', {
        headers: {
          'Authorization': `Bearer ${token.value}`,
          'Accept': 'application/json',
        },
      })
      if (!res.ok) { logout(); return }
      const data = await res.json()
      user.value = data.user
    } catch {
      logout()
    }
  }

  function logout() {
    fetch('/api/logout', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token.value}`, 'Accept': 'application/json' },
    }).catch(() => {})
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  return { user, token, isLoggedIn, isAdmin, isCoordinateur, entrepriseSlug, entrepriseBrand, login, fetchMe, logout }
})
