<script setup>
import { onMounted, ref } from 'vue'
import { useEmployee } from '../composables/useEmployee'
import { getCompanies } from '../services/companyService'
import EmployeeDetailsModal from '../components/employeeDetailsModal.vue'

const {
    employees,
    employee,
    loading,
    error,
    saving,
    pagination,
    fetchEmployees,
    fetchEmployee,
    saveEmployee,
    storeEmployee,
    removeEmployee,
} = useEmployee()

const showDetailsModal = ref(false)
const modalMode = ref('show')
const companyOptions = ref([])

onMounted(async () => {
    await Promise.all([
        fetchEmployees(),
        loadCompanyOptions(),
    ])
})

async function loadCompanyOptions() {
    const response = await getCompanies(1)
    companyOptions.value = response.data
}

function changePage(page) {
    fetchEmployees(page)
}

async function openDetails(employeeId) {
    modalMode.value = 'show'
    showDetailsModal.value = true

    await fetchEmployee(employeeId)
}

async function openEdit(employeeId) {
    modalMode.value = 'edit'
    showDetailsModal.value = true

    await fetchEmployee(employeeId)
}

function openCreate() {
    employee.value = null
    modalMode.value = 'create'
    showDetailsModal.value = true
}

function closeDetails() {
    showDetailsModal.value = false
}

async function handleUpdateEmployee(payload) {
    const updatedEmployee = await saveEmployee(employee.value.id, payload)

    employee.value = {
        ...employee.value,
        ...updatedEmployee,
    }

    await fetchEmployees(pagination.value.currentPage)

    showDetailsModal.value = false
    modalMode.value = 'show'
}

async function handleCreateEmployee(payload) {
    const createdEmployee = await storeEmployee(payload)

    await fetchEmployees(pagination.value.currentPage)

    employee.value = createdEmployee
    showDetailsModal.value = false
    modalMode.value = 'show'
}

async function handleDeleteEmployee(employeeId) {
    const confirmed = window.confirm('Deseja excluir este funcionário?')
    if (!confirmed) return

    await removeEmployee(employeeId)

    if (employee.value?.id === employeeId) {
        closeDetails()
        employee.value = null
    }

    await fetchEmployees(pagination.value.currentPage)
}
</script>

<template>
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Funcionários</h1>

            <button class="btn btn-primary" @click="openCreate">
                Novo funcionário
            </button>
        </div>

        <div v-if="loading" class="alert alert-info">
            Carregando funcionários...
        </div>

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div class="card" v-if="!loading && !error">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">Nome</th>
                            <th class="text-center">E-mail</th>
                            <th class="text-center">Cargo</th>
                            <th class="text-center">Empresa</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="employee in employees" :key="employee.id">
                            <td class="text-center">{{ employee.name }}</td>
                            <td class="text-center">{{ employee.email }}</td>
                            <td class="text-center">{{ employee.role }}</td>
                            <td class="text-center">{{ employee.company?.name ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary m-1" @click="openDetails(employee.id)">
                                    Visualizar
                                </button>

                                <button class="btn btn-sm btn-outline-warning m-1" @click="openEdit(employee.id)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>

                                <button class="btn btn-sm btn-outline-danger" @click="handleDeleteEmployee(employee.id)">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <nav class="mt-3" v-if="pagination.lastPage > 1">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: pagination.currentPage === 1 }">
                    <button class="page-link" @click="changePage(pagination.currentPage - 1)">Anterior</button>
                </li>

                <li class="page-item disabled">
                    <span class="page-link">Página {{ pagination.currentPage }} de {{ pagination.lastPage }}</span>
                </li>

                <li class="page-item" :class="{ disabled: pagination.currentPage === pagination.lastPage }">
                    <button class="page-link" @click="changePage(pagination.currentPage + 1)">Próxima</button>
                </li>
            </ul>
        </nav>

        <EmployeeDetailsModal
            v-if="showDetailsModal"
            :employee="employee"
            :companies="companyOptions"
            :mode="modalMode"
            :loading="loading"
            :saving="saving"
            :error="error"
            @close="closeDetails"
            @edit="modalMode = 'edit'"
            @save="modalMode === 'create' ? handleCreateEmployee($event) : handleUpdateEmployee($event)"
        />
    </main>
</template>
