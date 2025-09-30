<template>
  <div class="home">
    <a-layout>
      <a-layout-header>
        <div class="logo">
          <h2>Articles SPA</h2>
        </div>
      </a-layout-header>
      <a-layout-content>
        <div class="content">
          <a-card title="Добро пожаловать в Articles SPA">
            <p>Это Single Page Application, построенное на Vue 3 + TypeScript + Ant Design Vue</p>
            <a-button type="primary" size="large">
              Начать работу
            </a-button>
          </a-card>
        </div>
      </a-layout-content>
    </a-layout>
  </div>
</template>

<script setup lang="ts">
// Главная страница приложения
</script>

<style scoped>
.home {
  min-height: 100vh;
}

.logo {
  color: white;
  padding: 0 24px;
}

.content {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}
</style>
