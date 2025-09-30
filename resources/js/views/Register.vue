<template>
  <div class="register-container">
    <div class="register-form">
      <h2>Регистрация</h2>
      <a-form
        :model="formState"
        name="register"
        @finish="onFinish"
        @finish-failed="onFinishFailed"
        layout="vertical"
      >
        <a-form-item
          label="Имя"
          name="name"
          :rules="[{ required: true, message: 'Пожалуйста, введите имя!' }]"
        >
          <a-input v-model:value="formState.name" />
        </a-form-item>

        <a-form-item
          label="Email"
          name="email"
          :rules="[
            { required: true, message: 'Пожалуйста, введите email!' },
            { type: 'email', message: 'Пожалуйста, введите корректный email!' }
          ]"
        >
          <a-input v-model:value="formState.email" />
        </a-form-item>

        <a-form-item
          label="Пароль"
          name="password"
          :rules="[
            { required: true, message: 'Пожалуйста, введите пароль!' },
            { min: 8, message: 'Пароль должен содержать минимум 8 символов!' }
          ]"
        >
          <a-input-password v-model:value="formState.password" />
        </a-form-item>

        <a-form-item
          label="Подтверждение пароля"
          name="password_confirmation"
          :rules="[
            { required: true, message: 'Пожалуйста, подтвердите пароль!' },
            { validator: validatePasswordConfirmation }
          ]"
        >
          <a-input-password v-model:value="formState.password_confirmation" />
        </a-form-item>

        <a-form-item>
          <a-button
            type="primary"
            html-type="submit"
            :loading="authStore.loading"
            block
          >
            Зарегистрироваться
          </a-button>
        </a-form-item>

        <div class="register-footer">
          <span>Уже есть аккаунт? </span>
          <a @click="$router.push('/login')">Войти</a>
        </div>
      </a-form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { message } from 'ant-design-vue'

const router = useRouter()
const authStore = useAuthStore()

const formState = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const validatePasswordConfirmation = (_: any, value: string) => {
  if (value && value !== formState.password) {
    return Promise.reject(new Error('Пароли не совпадают!'))
  }
  return Promise.resolve()
}

const onFinish = async (values: any) => {
  const result = await authStore.register(
    values.name,
    values.email,
    values.password,
    values.password_confirmation
  )

  if (result.success) {
    message.success('Регистрация прошла успешно!')
    router.push('/dashboard')
  } else {
    message.error(result.error)
  }
}

const onFinishFailed = (errorInfo: any) => {
  console.log('Failed:', errorInfo)
}
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.register-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.register-form h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #333;
}

.register-footer {
  text-align: center;
  margin-top: 1rem;
}

.register-footer a {
  color: #1890ff;
  cursor: pointer;
  text-decoration: none;
}

.register-footer a:hover {
  text-decoration: underline;
}
</style>
