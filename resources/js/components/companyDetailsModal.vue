<script setup>
import { format as formatCNPJ } from '@fnando/cnpj'
import { reactive, watch } from 'vue'

const props = defineProps({
    company: {
        type: Object,
        required: false,
        default: null,
    },
    mode: {
        type: String,
        default: 'show'
    },
    loading: {
        type: Boolean,
        default: false,
    },
    saving: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
        required: false,
        default: null
    }
})

const emit = defineEmits(['close', 'edit', 'save'])

const form = reactive({
    name: '',
    cnpj: '',
    status: 'active'
})

const errors = reactive({
    name: null,
    cnpj: null,
    status: null,
})

watch(
    () => [props.company, props.mode],
    ([company, mode]) => {
        if (mode === 'create') {
            form.name = ''
            form.cnpj = ''
            form.status = 'active'
            clearErrors()
            return
        }

        if (!company) return

        form.name = company.name
        form.cnpj = formatCNPJ(company.cnpj)
        form.status = company.status ? 'active' : 'inactive'
        clearErrors()
    },
    { immediate: true }
)

function clearErrors() {
    errors.name = null
    errors.cnpj = null
    errors.status = null
}

function validateForm() {
    clearErrors()

    if (!form.name) {
        errors.name = 'Nome da empresa é obrigatório.'
    }

    if (!form.cnpj) {
        errors.cnpj = 'CNPJ é obrigatório.'
    } else if (form.cnpj.replace(/\D/g, '').length !== 14) {
        errors.cnpj = 'CNPJ deve conter 14 números.'
    }

    if (!['active', 'inactive'].includes(form.status)) {
        errors.status = 'Status é obrigatório.'
    }

    return !errors.name && !errors.cnpj && !errors.status
}

function submit() {
    if (!validateForm()) return

    emit('save', {
        name: form.name,
        cnpj: form.cnpj,
        status: form.status,
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
                        {{ mode === 'edit' ? 'Editar empresa' : mode === 'show' ? 'Detalhes da empresa' : 'Criar empresa' }}
                    </h5>

                    <button type="button" class="btn-close" @click="closeModal"></button>
                </div>

                <div class="modal-body">
                    <div v-if="loading" class="alert alert-info">
                        Carregando detalhes...
                    </div>

                    <div v-else-if="company || mode === 'create'">
                        <div v-if="error" class="alert alert-danger">
                            {{ error }}
                        </div>

                        <!-- MODO VISUALIZAÇÃO -->
                        <div v-if="mode === 'show'">
                            <h6 class="fw-bold mb-3">Dados da empresa</h6>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nome</p>
                                    <p class="fw-semibold">{{ company.name }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">CNPJ</p>
                                    <p class="fw-semibold">{{ formatCNPJ(company.cnpj) }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">Status</p>

                                    <span class="badge"
                                        :class="company.status ? 'text-bg-success' : 'text-bg-secondary'">
                                        {{ company.status ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- MODO EDIÇÃO -->
                        <form v-else @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">Nome da empresa</label>

                                <input v-model="form.name" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors.name }">

                                <div v-if="errors.name" class="invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CNPJ</label>

                                <input v-model="form.cnpj" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors.cnpj }">

                                <div v-if="errors.cnpj" class="invalid-feedback">
                                    {{ errors.cnpj }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>

                                <select v-model="form.status" class="form-select"
                                    :class="{ 'is-invalid': errors.status }">
                                    <option value="active">Ativa</option>
                                    <option value="inactive">Inativa</option>
                                </select>

                                <div v-if="errors.status" class="invalid-feedback">
                                    {{ errors.status }}
                                </div>
                            </div>
                        </form>

                        <template v-if="company">
                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    Funcionários vinculados
                                </h6>

                                <span class="badge text-bg-primary">
                                    {{ company.employees?.length || 0 }}
                                </span>
                            </div>

                            <div v-if="!company.employees || company.employees.length === 0"
                                class="alert alert-warning">
                                Nenhum funcionário vinculado a esta empresa.
                            </div>

                            <table v-else class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Cargo</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="employee in company.employees" :key="employee.id">
                                        <td>{{ employee.name }}</td>
                                        <td>{{ employee.email }}</td>
                                        <td>{{ employee.role }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Fechar
                    </button>

                    <button v-if="mode === 'show'" type="button" class="btn btn-warning" @click="$emit('edit')">
                        Editar
                    </button>

                    <button v-if="mode === 'edit' || mode === 'create'" type="button" class="btn btn-primary"
                        :disabled="saving" @click="submit">
                        {{ saving ? 'Salvando...' : 'Salvar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop fade show"></div>
</template>
