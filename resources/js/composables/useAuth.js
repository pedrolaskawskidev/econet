import { reactive } from 'vue'
import axios from 'axios'
import api from '../services/api'

const state = reactive({
    user: null,
    loading: false,
    initialized: false,
})

async function fetchUser() {
    state.loading = true

    try {
        const response = await api.get('/user')
        state.user = response.data
    } catch (error) {
        if (error.response?.status === 401) {
            state.user = null
        } else {
            throw error
        }
    } finally {
        state.loading = false
        state.initialized = true
    }

    return state.user
}

async function initializeAuth() {
    if (state.initialized) {
        return state.user
    }

    return fetchUser()
}

async function login(credentials) {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
    await api.post('/login', credentials)
    return fetchUser()
}

async function logout() {
    await api.post('/logout')
    state.user = null
    state.initialized = true
}

export function useAuth() {
    return {
        state,
        initializeAuth,
        fetchUser,
        login,
        logout,
    }
}
