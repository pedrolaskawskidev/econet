<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from './composables/useAuth'

const router = useRouter()
const { state, logout } = useAuth()

const isAuthenticated = computed(() => Boolean(state.user))

async function handleLogout() {
    await logout()
    router.push('/login')
}
</script>

<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <RouterLink class="navbar-brand" to="/">
                    Cadastros
                </RouterLink>

                <div class="navbar-nav">
                    <RouterLink v-if="isAuthenticated" class="nav-link" to="/companies">
                        Empresas
                    </RouterLink>
                    <RouterLink v-if="isAuthenticated" class="nav-link" to="/employees">
                        Funcionários
                    </RouterLink>
                    <RouterLink v-if="!isAuthenticated" class="nav-link" to="/login">
                        Login
                    </RouterLink>
                    <button v-if="isAuthenticated" class="btn btn-link nav-link" @click="handleLogout">
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <RouterView />
    </div>
</template>
