<template>
  <div class="home">
    <a-layout>
      <a-layout-header class="header">
        <div class="logo">
          <FileTextOutlined class="logo-icon" />
          <h2>Articles SPA</h2>
        </div>
        <div class="header-actions">
          <div class="auth-buttons" v-if="!authStore.isAuthenticated">
            <a-button @click="$router.push('/login')" type="primary">
              Войти
            </a-button>
            <a-button @click="$router.push('/register')">
              Регистрация
            </a-button>
          </div>

          <div class="user-menu" v-else>
            <span class="user-name">Добро пожаловать, {{ authStore.user?.name || 'Пользователь' }}!</span>
            <a-button @click="$router.push('/dashboard')" type="primary">
              Панель управления
            </a-button>
            <a-button @click="handleLogout" danger>
              Выйти
            </a-button>
          </div>
        </div>
      </a-layout-header>
      <a-layout-content>
        <div class="content">

          <!-- Карточки статей -->
          <div class="articles-section">
            <a-row :gutter="[16, 16]">
              <a-col :span="24">
                <h2 class="section-title">
                  <FileTextOutlined />
                  Последние статьи
                </h2>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 16]" v-if="loading">
              <a-col :span="24">
                <a-spin size="large" style="width: 100%; text-align: center; padding: 50px;">
                  <template #indicator>
                    <LoadingOutlined style="font-size: 24px" spin />
                  </template>
                  Загрузка статей...
                </a-spin>
              </a-col>
            </a-row>

            <a-row :gutter="[16, 16]" v-else-if="articles.length === 0">
              <a-col :span="24">
                <a-empty description="Статьи не найдены" />
              </a-col>
            </a-row>

            <a-row :gutter="[16, 16]" v-else>
              <a-col
                v-for="article in articles"
                :key="article.id"
                :xs="24"
                :sm="12"
                :md="8"
                :lg="6"
              >
                <a-card
                  class="article-card"
                  hoverable
                  @click="handleArticleClick(article.id)"
                >
                  <template #title>
                    <div class="article-title">
                      {{ article.title }}
                    </div>
                  </template>

                  <div class="article-meta">
                    <div class="article-meta-row">
                      <p class="article-author">
                        <UserOutlined />
                        {{ article.author.name }}
                      </p>
                      <a-tag color="blue" class="comments-tag">
                        <MessageOutlined />
                        {{ article.comments_count || 0 }}
                      </a-tag>
                    </div>
                    <p class="article-date">
                      <CalendarOutlined />
                      {{ formatDate(article.created_at) }}
                    </p>
                  </div>

                  <div class="article-content">
                    <p>{{ truncateText(article.content, 100) }}</p>
                  </div>

                  <template #actions>
                    <a-button type="link" size="small" @click.stop="handleArticleClick(article.id)">
                      <EyeOutlined />
                      Читать
                    </a-button>
                  </template>
                </a-card>
              </a-col>
            </a-row>

            <!-- Пагинация -->
            <a-row v-if="articles.length > 0">
              <a-col :span="24" style="text-align: center; margin-top: 24px;">
                <a-pagination
                  v-model:current="currentPage"
                  v-model:page-size="perPage"
                  :total="totalArticles"
                  show-size-changer
                  show-quick-jumper
                  :page-size-options="['6', '12', '24', '48']"
                  :show-total="(total, range) => `Показано ${range[0]}-${range[1]} из ${total} статей`"
                  :show-size-changer-text="'Элементов на странице:'"
                  :show-quick-jumper-text="'Перейти к странице:'"
                  @change="handlePageChange"
                  @show-size-change="handlePageSizeChange"
                />
              </a-col>
            </a-row>
          </div>

          <!-- Технические детали для авторизированных пользователей -->
          <div v-if="authStore.isAuthenticated">
            <a-divider>
              <SettingOutlined />
              Технические детали
            </a-divider>

            <a-row :gutter="[16, 16]">
              <a-col :span="24">
                <a-descriptions title="Используемые технологии" bordered>
                  <a-descriptions-item label="Frontend">
                    <a-tag color="green">Vue 3</a-tag>
                    <a-tag color="blue">TypeScript</a-tag>
                    <a-tag color="purple">Ant Design Vue</a-tag>
                  </a-descriptions-item>
                  <a-descriptions-item label="Backend">
                    <a-tag color="orange">Laravel</a-tag>
                    <a-tag color="cyan">PHP 8.3</a-tag>
                    <a-tag color="geekblue">PostgreSQL</a-tag>
                  </a-descriptions-item>
                  <a-descriptions-item label="Инструменты">
                    <a-tag color="red">Vite</a-tag>
                    <a-tag color="volcano">Docker</a-tag>
                    <a-tag color="gold">NGINX</a-tag>
                  </a-descriptions-item>
                </a-descriptions>
              </a-col>
            </a-row>

            <a-divider>
              <SmileOutlined />
              Демонстрация иконок
            </a-divider>

            <IconDemo />
          </div>
        </div>
      </a-layout-content>
      <a-layout-footer class="footer">
        <div class="footer-content">
          <span>
            <CopyrightOutlined />
            Articles SPA © 2025
          </span>
          <a-space>
            <a href="https://github.com/gfmweb" target="_blank" rel="noopener noreferrer" class="footer-link">
              <GithubOutlined />
              GitHub
            </a>
            <a href="/docs" target="_blank" rel="noopener noreferrer" class="footer-link">
              <QuestionCircleOutlined />
              Помощь
            </a>
          </a-space>
        </div>
      </a-layout-footer>
    </a-layout>

    <!-- Модальное окно для просмотра статьи -->
    <ArticleModal
      :article-id="selectedArticleId"
      v-model:visible="modalVisible"
      :current-user-id="authStore.user?.id"
      @article-updated="handleArticleUpdated"
      @article-deleted="handleArticleDeleted"
      @comment-deleted="handleCommentDeleted"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { message } from 'ant-design-vue'
import { articlesService, type Article } from '../services/articles'
import IconDemo from '../components/IconDemo.vue'
import ArticleModal from '../components/ArticleModal.vue'

const router = useRouter()
const authStore = useAuthStore()

// Состояние для статей
const articles = ref<Article[]>([])
const loading = ref(false)
const currentPage = ref(1)
const totalArticles = ref(0)
const perPage = ref(12)

// Состояние для модального окна
const selectedArticleId = ref<number | null>(null)
const modalVisible = ref(false)

// Загрузка статей
const loadArticles = async (page: number = 1) => {
  try {
    loading.value = true
    const response = await articlesService.getArticles(page, perPage.value)
    articles.value = response.data
    totalArticles.value = response.meta.total
    currentPage.value = page
  } catch (error) {
    console.error('Ошибка загрузки статей:', error)
    message.error('Не удалось загрузить статьи')
  } finally {
    loading.value = false
  }
}

// Обработка изменения размера страницы
const handlePageSizeChange = (current: number, size: number) => {
  perPage.value = size
  currentPage.value = 1
  loadArticles(1)
}

// Обработка изменения страницы
const handlePageChange = (page: number) => {
  currentPage.value = page
  loadArticles(page)
}

// Обработка клика по карточке статьи
const handleArticleClick = (articleId: number) => {
  if (!authStore.isAuthenticated) {
    message.warning('Для просмотра статьи необходимо войти в систему')
    router.push('/login')
  } else {
    selectedArticleId.value = articleId
    modalVisible.value = true
  }
}

// Обработка закрытия модального окна
const handleModalClose = () => {
  modalVisible.value = false
  selectedArticleId.value = null
}

// Обработка обновления статьи
const handleArticleUpdated = () => {
  loadArticles(currentPage.value)
}

// Обработка удаления статьи
const handleArticleDeleted = () => {
  loadArticles(currentPage.value)
}

// Обработка удаления комментария
const handleCommentDeleted = () => {
  loadArticles(currentPage.value)
}

// Форматирование даты
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Обрезка текста
const truncateText = (text: string, maxLength: number) => {
  if (text.length <= maxLength) return text
  return text.substring(0, maxLength) + '...'
}

// Выход из системы
const handleLogout = async () => {
  await authStore.logout()
  message.success('Вы успешно вышли из системы')
  router.push('/')
}

// Загрузка статей при монтировании компонента
onMounted(() => {
  loadArticles()
})
</script>

<style scoped>
.home {
  min-height: 100vh;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.auth-buttons {
  display: flex;
  gap: 8px;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-name {
  color: white;
  font-size: 14px;
}

.logo {
  display: flex;
  align-items: center;
  color: white;
  gap: 8px;
}

.logo-icon {
  font-size: 24px;
}

.content {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}


.feature-card {
  height: 100%;
  transition: all 0.3s;
}

.feature-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.footer {
  text-align: center;
  background: #f0f2f5;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

.footer-link {
  color: #666;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 4px;
}

.footer-link:hover {
  color: #1890ff;
}

/* Стили для секции статей */
.articles-section {
  margin-top: 24px;
}

.section-title {
  font-size: 24px;
  font-weight: 600;
  color: #262626;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.article-card {
  height: 100%;
  max-height: 280px;
  transition: all 0.3s ease;
  cursor: pointer;
  border-radius: 8px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.article-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.article-card :deep(.ant-card-head) {
  border-bottom: 1px solid #f0f0f0;
  padding: 16px 20px;
}

.article-card :deep(.ant-card-head-title) {
  font-size: 16px;
  font-weight: 600;
  color: #262626;
  line-height: 1.4;
  padding: 0;
  white-space: normal;
  word-wrap: break-word;
  word-break: break-word;
}

.article-title {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.4;
  margin: 0;
  word-wrap: break-word;
  word-break: break-word;
  hyphens: auto;
  width: 100%;
  max-width: 100%;
}

.article-meta-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 4px;
}

.comments-tag {
  margin: 0;
  font-size: 12px;
  line-height: 1.2;
  white-space: nowrap;
}

.article-card :deep(.ant-card-body) {
  padding: 16px 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.article-card :deep(.ant-card-actions) {
  background: #fafafa;
  border-top: 1px solid #f0f0f0;
  margin-top: auto;
  flex-shrink: 0;
}

.article-meta {
  margin-bottom: 12px;
}

.article-author,
.article-date {
  margin: 0;
  font-size: 12px;
  color: #8c8c8c;
  display: flex;
  align-items: center;
  gap: 4px;
}

.article-date {
  margin-bottom: 4px;
}

.article-content {
  margin-bottom: 16px;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.article-content p {
  margin: 0;
  font-size: 14px;
  color: #595959;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  flex: 1;
}

/* Адаптивность */
@media (max-width: 768px) {
  .section-title {
    font-size: 20px;
  }

  .article-card {
    max-height: 260px;
  }

  .article-title {
    font-size: 14px;
  }

  .comments-tag {
    font-size: 11px;
  }

  .article-content p {
    font-size: 13px;
  }

  .article-meta-row {
    gap: 6px;
  }
}

@media (max-width: 480px) {
  .article-card {
    max-height: 240px;
  }

  .article-title {
    font-size: 13px;
  }

  .article-meta-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }

  .comments-tag {
    align-self: flex-end;
  }
}
</style>
