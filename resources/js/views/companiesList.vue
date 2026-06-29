<script setup>
import { onMounted } from 'vue'
import { useCompanies } from '../composables/useCompanies'


const {
    companies,
    loading,
    error,
    pagination,
    fetchCompanies,
} = useCompanies()

onMounted(() => {
    fetchCompanies()
})

function changePage(page){
    fetchCompanies(page)
}
</script>

<template>
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Empresas</h1>

            <button class="btn btn-primary">
                Nova empresa
            </button>
        </div>

        <div v-if="loading" class="alert alert-info">
            Carregando empresas...
        </div>

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div class="card" v-if="!loading && !error">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="company in companies" :key="company.id">
                            <td>{{ company.name }}</td>
                            <td>{{ company.cnpj }}</td>
                            <td>
                                <span class="badge" :class="company.status === 'active' ? 'text-bg-success' : 'text-bg-secondary'">
                                    {{ company.status === 'active' ? 'Ativa' : 'Inativa' }}
                                </span>
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
    </main>
</template>