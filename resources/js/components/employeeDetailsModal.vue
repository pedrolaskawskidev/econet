<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
    employee: {
        type: Object,
        required: false,
        default: null,
    },
    companies: {
        type: Array,
        default: () => [],
    },
    mode: {
        type: String,
        default: 'show',
    },
    loading: {
        type: Boolean,
        default: false,
    },
    saving: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        required: false,
        default: null,
    },
})

const emit = defineEmits(['close', 'edit', 'save'])

const form = reactive({
    company_id: '',
    name: '',
    email: '',
    role: '',
})

const errors = reactive({
    company_id: null,
    name: null,
    email: null,
    role: null,
})

watch(
    () => [props.employee, props.mode],
    ([employee, mode]) => {
        if (mode === 'create') {
            form.company_id = ''
            form.name = ''
            form.email = ''
            form.role = ''
            clearErrors()
            return
        }

        if (!employee) return

        form.company_id = employee.company_id
        form.name = employee.name
        form.email = employee.email
        form.role = employee.role
        clearErrors()
    },
    { immediate: true }
)

function clearErrors() {
    errors.company_id = null
    errors.name = null
    errors.email = null
    errors.role = null
}

function validateForm() {
    clearErrors()

    if (!form.company_id) {
        errors.company_id = 'Empresa é obrigatória.'
    }

    if (!form.name) {
        errors.name = 'Nome do funcionário é obrigatório.'
    }

    if (!form.email) {
        errors.email = 'E-mail é obrigatório.'
    }

    if (!form.role) {
        errors.role = 'Cargo é obrigatório.'
    }

    return !errors.company_id && !errors.name && !errors.email && !errors.role
}

function submit() {
    if (!validateForm()) return

    emit('save', {
        company_id: Number(form.company_id),
        name: form.name,
        email: form.email,
        role: form.role,
    })
}

function closeModal() {
    emit('close')
}
</script>

<template>
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ mode === 'edit' ? 'Editar funcionário' : mode === 'show' ? 'Detalhes do funcionário' : 'Criar funcionário' }}
                    </h5>

                    <button type="button" class="btn-close" @click="closeModal"></button>
                </div>

                <div class="modal-body">
                    <div v-if="loading" class="alert alert-info">
                        Carregando detalhes...
                    </div>

                    <div v-else-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>

                    <div v-else-if="employee || mode === 'create'">
                        <div v-if="mode === 'show'">
                            <h6 class="fw-bold mb-3">Dados do funcionário</h6>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nome</p>
                                    <p class="fw-semibold">{{ employee.name }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">E-mail</p>
                                    <p class="fw-semibold">{{ employee.email }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Cargo</p>
                                    <p class="fw-semibold">{{ employee.role }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Empresa</p>
                                    <p class="fw-semibold">{{ employee.company?.name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <form v-else @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">Empresa</label>

                                <select
                                    v-model="form.company_id"
                                    class="form-select"
                                    :class="{ 'is-invalid': errors.company_id }"
                                >
                                    <option value="">Selecione uma empresa</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>

                                <div v-if="errors.company_id" class="invalid-feedback">
                                    {{ errors.company_id }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nome</label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.name }"
                                >

                                <div v-if="errors.name" class="invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">E-mail</label>

                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.email }"
                                >

                                <div v-if="errors.email" class="invalid-feedback">
                                    {{ errors.email }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cargo</label>

                                <input
                                    v-model="form.role"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.role }"
                                >

                                <div v-if="errors.role" class="invalid-feedback">
                                    {{ errors.role }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Fechar
                    </button>

                    <button v-if="mode === 'show'" type="button" class="btn btn-warning" @click="$emit('edit')">
                        Editar
                    </button>

                    <button v-if="mode === 'edit' || mode === 'create'" type="button" class="btn btn-primary" :disabled="saving" @click="submit">
                        {{ saving ? 'Salvando...' : 'Salvar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop fade show"></div>
</template>
