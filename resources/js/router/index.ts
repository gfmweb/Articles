import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: false, redirectIfAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false, guestOnly: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { requiresAuth: false, guestOnly: true }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Защита маршрутов
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()

    // Инициализируем авторизацию при первом запуске
    if (!authStore.token && localStorage.getItem('token')) {
        await authStore.initAuth()
    }

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const guestOnly = to.matched.some(record => record.meta.guestOnly)
    const redirectIfAuth = to.matched.some(record => record.meta.redirectIfAuth)

    if (requiresAuth && !authStore.isAuthenticated) {
        // Если маршрут требует авторизации, но пользователь не авторизован
        next('/login')
    } else if (guestOnly && authStore.isAuthenticated) {
        // Если маршрут только для гостей, но пользователь авторизован
        next('/dashboard')
    } else if (redirectIfAuth && authStore.isAuthenticated) {
        // Если маршрут должен перенаправлять авторизованных пользователей
        next('/dashboard')
    } else {
        next()
    }
})

export default router
