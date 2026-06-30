<script setup>
import { onMounted, ref } from 'vue';
import { format as formatCNPJ } from '@fnando/cnpj';
import { useCompanies } from '../composables/useCompanies';
import CompanyDetailsModal from '../components/companyDetailsModal.vue';


const {
    companies,
    company,
    loading,
    error,
    saving,
    pagination,
    fetchCompanies,
    fetchCompany,
    saveCompany,
    storeCompany,
    removeCompany,
    clearCompanyState
} = useCompanies()

const showDetailsModal = ref(false);
const modalMode = ref('show');

onMounted(() => {
    fetchCompanies()
});

function changePage(page) {
    fetchCompanies(page)
}

async function openDetails(companyId) {
    clearCompanyState()
    modalMode.value = 'show';
    showDetailsModal.value = true;

    await fetchCompany(companyId)
}

async function openEdit(companyId) {
    clearCompanyState()
    modalMode.value = 'edit';
    showDetailsModal.value = true;

    await fetchCompany(companyId)
}

async function openCreate() {
    clearCompanyState()
    company.value = null;
    modalMode.value = 'create';
    showDetailsModal.value = true;
}

function closeDetails() {
    clearCompanyState()
    showDetailsModal.value = false;
}

async function handleUpdateCompany(payload) {
    const updatedCompany = await saveCompany(company.value.id, payload)

    company.value = {
        ...company.value,
        ...updatedCompany
    }

    await fetchCompanies(pagination.value.currentPage)

    showDetailsModal.value = false
    modalMode.value = 'show'
}

async function handleCreateCompany(payload) {
    const createdCompany = await storeCompany(payload)

    await fetchCompanies(pagination.value.currentPage)

    company.value = createdCompany
    showDetailsModal.value = false
    modalMode.value = 'show'
}

async function handleDeleteCompany(companyId) {
    const confirmed = window.confirm('Deseja excluir ?')
    if (!confirmed) return

    await removeCompany(companyId)

    if (company.value?.id === companyId) {
        closeDetails()
        company.value = null
    }

    await fetchCompanies(pagination.value.currentPage)
}

</script>

<template>
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Empresas</h1>

            <button class="btn btn-primary" @click="openCreate">
                Nova empresa
            </button>
        </div>

        <div v-if="loading" class="alert alert-info">
            Carregando empresas...
        </div>

        <div v-if="error && !showDetailsModal" class="alert alert-danger">
            {{ error }}
        </div>

        <div class="card" v-if="!loading && !error">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">Nome</th>
                            <th class="text-center">CNPJ</th>
                            <th colspan="2" class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="company in companies" :key="company.id">
                            <td class="text-center">{{ company.name }}</td>
                            <td class="text-center">{{ formatCNPJ(company.cnpj) }}</td>
                            <td class="text-center">
                                <span class="badge" :class="company.status ? 'text-bg-success' : 'text-bg-secondary'">
                                    {{ company.status ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary m-1" @click="openDetails(company.id)">
                                    Visualizar
                                </button>

                                <button class="btn btn-sm btn-outline-warning m-1" @click="openEdit(company.id)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>

                                <button class="btn btn-sm btn-outline-danger" @click="handleDeleteCompany(company.id)">
                                    <i class="bi bi-trash"></i>Excluir
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
                    <span class="page-link"> Página {{ pagination.currentPage }} de {{ pagination.lastPage }}</span>
                </li>

                <li class="page-item" :class="{ disabled: pagination.currentPage === pagination.lastPage }">
                    <button class="page-link" @click="changePage(pagination.currentPage + 1)">Próxima</button>
                </li>
            </ul>
        </nav>

        <CompanyDetailsModal v-if="showDetailsModal" :company="company" :mode="modalMode" :loading="loading"
            :saving="saving" :error="error" @close="closeDetails" @edit="modalMode = 'edit'"
            @save="modalMode === 'create' ? handleCreateCompany($event) : handleUpdateCompany($event)" />
    </main>
</template>
