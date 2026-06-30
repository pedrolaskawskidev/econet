<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { login } = useAuth()

const form = reactive({
    email: '',
    password: '',
    remember: false,
})

const loading = ref(false)
const error = ref(null)

async function submit() {
    loading.value = true
    error.value = null

    try {
        await login(form)
        router.push('/companies')
    } catch (err) {
        error.value = err.response?.data?.message
            ?? err.response?.data?.errors?.email?.[0]
            ?? 'Não foi possível realizar o login.'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h3 mb-4 text-center">Login</h1>

                        <div v-if="error" class="alert alert-danger">
                            {{ error }}
                        </div>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input v-model="form.email" type="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input v-model="form.password" type="password" class="form-control" required>
                            </div>

                            <div class="form-check mb-4">
                                <input id="remember" v-model="form.remember" type="checkbox" class="form-check-input">
                                <label class="form-check-label" for="remember">
                                    Lembrar de mim
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                                {{ loading ? 'Entrando...' : 'Entrar' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
