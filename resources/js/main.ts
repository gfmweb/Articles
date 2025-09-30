import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import Antd from 'ant-design-vue'
import * as Icons from '@ant-design/icons-vue'
import 'ant-design-vue/dist/reset.css'
import './bootstrap'
import { useAuthStore } from './stores/auth'
import ruRU from 'ant-design-vue/es/locale/ru_RU'

const app = createApp(App)

// Регистрируем все иконки глобально
const icons = Icons as any
for (const i in icons) {
    app.component(i, icons[i])
}

const pinia = createPinia()
app.use(pinia)
app.use(router)
app.use(Antd)

// Настройка русской локализации
app.config.globalProperties.$locale = ruRU

// Инициализируем авторизацию при запуске приложения
const authStore = useAuthStore()
authStore.initAuth().then(() => {
    app.mount('#app')
})
