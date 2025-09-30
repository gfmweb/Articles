import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(localStorage.getItem('token'))
    const user = ref<any>(null)
    const loading = ref(false)

    const isAuthenticated = computed(() => !!token.value)

    const setToken = (newToken: string) => {
        token.value = newToken
        localStorage.setItem('token', newToken)
        api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
    }

    const clearToken = () => {
        token.value = null
        user.value = null
        localStorage.removeItem('token')
        delete api.defaults.headers.common['Authorization']
    }

    const login = async (email: string, password: string) => {
        loading.value = true
        try {
            const response = await api.post('/login', {
                email,
                password
            })

            const { token: newToken, user: userData } = response.data.data
            setToken(newToken)
            user.value = userData

            return { success: true }
        } catch (error: any) {
            return {
                success: false,
                error: error.response?.data?.message || 'Ошибка авторизации'
            }
        } finally {
            loading.value = false
        }
    }

    const register = async (name: string, email: string, password: string, password_confirmation: string) => {
        loading.value = true
        try {
            const response = await api.post('/register', {
                name,
                email,
                password,
                password_confirmation
            })

            const { token: newToken, user: userData } = response.data.data
            setToken(newToken)
            user.value = userData

            return { success: true }
        } catch (error: any) {
            return {
                success: false,
                error: error.response?.data?.message || 'Ошибка регистрации'
            }
        } finally {
            loading.value = false
        }
    }

    const logout = async () => {
        try {
            await api.post('/logout')
        } catch (error) {
            console.error('Logout API error:', error)
        } finally {
            clearToken()
        }
    }

    const fetchUser = async () => {
        try {
            const response = await api.get('/me')
            user.value = response.data.data
            return { success: true }
        } catch (error: any) {
            return {
                success: false,
                error: error.response?.data?.message || 'Ошибка получения данных пользователя'
            }
        }
    }

    const initAuth = async () => {
        if (token.value) {
            api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
            // Получаем данные пользователя при инициализации
            await fetchUser()
        }
    }

    return {
        token,
        user,
        loading,
        isAuthenticated,
        setToken,
        clearToken,
        login,
        register,
        logout,
        fetchUser,
        initAuth
    }
})
