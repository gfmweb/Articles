<template>
  <div class="login-container">
    <div class="login-form">
      <h2>Вход в систему</h2>
      <a-form
        :model="formState"
        name="login"
        @finish="onFinish"
        @finish-failed="onFinishFailed"
        layout="vertical"
      >
        <a-form-item
          label="Email"
          name="email"
          :rules="[{ required: true, message: 'Пожалуйста, введите email!' }]"
        >
          <a-input v-model:value="formState.email" />
        </a-form-item>

        <a-form-item
          label="Пароль"
          name="password"
          :rules="[{ required: true, message: 'Пожалуйста, введите пароль!' }]"
        >
          <a-input-password v-model:value="formState.password" />
        </a-form-item>

        <a-form-item>
          <a-button
            type="primary"
            html-type="submit"
            :loading="authStore.loading"
            block
          >
            Войти
          </a-button>
        </a-form-item>

        <div class="login-footer">
          <span>Нет аккаунта? </span>
          <a @click="$router.push('/register')">Зарегистрироваться</a>
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
  email: '',
  password: ''
})

const onFinish = async (values: any) => {
  const result = await authStore.login(values.email, values.password)

  if (result.success) {
    message.success('Успешный вход!')
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
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.login-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.login-form h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #333;
}

.login-footer {
  text-align: center;
  margin-top: 1rem;
}

.login-footer a {
  color: #1890ff;
  cursor: pointer;
  text-decoration: none;
}

.login-footer a:hover {
  text-decoration: underline;
}
</style>
