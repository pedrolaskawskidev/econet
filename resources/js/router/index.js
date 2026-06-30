import { createRouter, createWebHistory } from "vue-router"

import { useAuth } from "../composables/useAuth"
import CompaniesList from "../views/companiesList.vue"
import EmployeesList from "../views/employeesList.vue"
import LoginView from "../views/loginView.vue"


const routes = [
    {
        path: '/',
        redirect: '/companies'
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
        meta: {
            guestOnly: true,
        },
    },
    {
        path: '/companies',
        name: 'companies.index',
        component: CompaniesList,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/employees',
        name: 'employees.index',
        component: EmployeesList,
        meta: {
            requiresAuth: true,
        },
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to) => {
    const { state, initializeAuth } = useAuth()

    await initializeAuth()

    if (to.meta.requiresAuth && !state.user) {
        return '/login'
    }

    if (to.meta.guestOnly && state.user) {
        return '/companies'
    }
})

export default router
