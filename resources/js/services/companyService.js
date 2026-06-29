import api from './api'

export async function getCompanies(page =1) {
    const response = await api.get('/companies', {
        params: {
            page,
        },
    })

    return response.data
}
