import { ref } from "vue"

import {
    getCompanies
} from '../services/companyService'


export function useCompanies() {
    const companies = ref([])
    const company = ref(null)

    const loading = ref(false)
    const error = ref(null)
    const success = ref(null)

    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 10,
        total: 0
    })

    async function fetchCompanies(page = 1) {
        loading.value = true
        error.value = null

        try {
            const response = await getCompanies(page)

            companies.value = response.data

            pagination.value = {
                currentPage: response.current_page,
                lastPage: response.last_page,
                perPage: response.per_page,
                total: response.total,
            }
        } catch (err) {
            error.value = 'Erro ao carregar empresas.'
        } finally {
            loading.value = false
        }

    }

     return {
        companies,
        company,
        loading,
        error,
        success,
        pagination,
        fetchCompanies,
    }
}