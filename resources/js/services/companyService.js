import api from './api'

export async function getCompanies(page = 1) {
    const response = await api.get('/companies', {
        params: {
            page,
        },
    });

    return response.data;
}

export async function createCompany(payload) {
    const response = await api.post('/companies', payload);

    return response.data;
}

export async function getCompany(id) {
    const response = await api.get(`/companies/${id}`);

    return response.data.data;
}

export async function updateCompany(id, payload) {
    const response = await api.put(`/companies/${id}`, payload);

    return response.data;
}

export async function deleteCompany(id) {
    const response = await api.delete(`/companies/${id}`);

    return response.data;
}