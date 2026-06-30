import { ref } from "vue";


import {
    getEmployees,
    getEmployee,
    createEmployee,
    updateEmployee,
    deleteEmployee
} from '../services/employeeService'

export function useEmployee() {
    const employees = ref([]);
    const employee = ref(null);

    const loading = ref(false);
    const error = ref(null);
    const success = ref(null);

    const saving = ref(false);
    const deleting = ref(false);

    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 10,
        total: 0
    })

    async function fetchEmployees(page = 1) {
        loading.value = true;
        error.value = null;

        try {
            const response = await getEmployees(page);

            employees.value = response.data;

            pagination.value = {
                currentPage: response.current_page,
                lastPage: response.last_page,
                perPage: response.per_page,
                total: response.total,
            }
        } catch (err) {
            error.value = 'Erro ao carregar funcionários.';
        } finally {
            loading.value = false;
        }
    }

    async function fetchEmployee(id) {
        loading.value = true;
        error.value = null;
        employee.value = null;

        try {
            employee.value = await getEmployee(id);
        } catch (err) {
            error.value = 'Erro ao carregar funcionário';
        } finally {
            loading.value = false;
        }
    }

    async function saveEmployee(id, payload) {
        saving.value = true;
        error.value = null;
        success.value = null;

        try {
            const response = await updateEmployee(id, payload);

            success.value = 'Funcionário atualizado com sucesso';

            return response.data;
        } catch (err) {
            if (err.response?.status === 422) {
                error.value = err.response?.data?.message ?? 'Dados inválidos para atualização.'
                throw err;
            }
            error.value = 'Erro ao atualizar';
            throw err
        } finally {
            saving.value = false
        }

    }

    async function storeEmployee(payload) {
        saving.value = true;
        error.value = null;
        success.value = null;

        try {
            const response = await createEmployee(payload);

            success.value = "Funcionário criado com sucesso!";

            return response.data;
        } catch (err) {
            if (err.response?.status === 422) {
                error.value = err.response?.data?.message ?? 'Dados inválidos para cadastro.'
                throw err;
            }

            error.value = 'Erro ao cadastrar funcionário.';
            throw err
        } finally {
            saving.value = false;
        }
    }

    async function removeEmployee(id) {
        deleting.value = true;
        error.value = null;
        success.value = null;

        try {
            await deleteEmployee(id);

            employees.value = employees.value.filter((item) => item.id !== id);

            success.value = 'Funcionário excluído com sucesso.';
        } catch (err) {
            error.value = 'Erro ao excluir funcionário.';
            throw err
        } finally {
            deleting.value = false;
        }

    }

return {
        employees,
        employee,
        loading,
        error,
        success,
        saving,
        deleting,
        pagination,
        fetchEmployees,
        fetchEmployee,
        saveEmployee,
        storeEmployee,
        removeEmployee
    }

}
