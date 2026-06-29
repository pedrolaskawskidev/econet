import { createRouter, createWebHistory } from "vue-router"

import CompaniesList from "../views/companiesList.vue"

const routes = [
    {
        path: '/',
        redirect: '/companies'
    },
    {
        path: 'companies',
        name: 'companies.index',
        component: CompaniesList
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router

