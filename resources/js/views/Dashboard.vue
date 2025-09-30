<template>
  <div class="dashboard">
    <a-layout>
      <a-layout-header class="header">
        <div class="header-content">
          <h1>Панель управления</h1>
          <div class="user-info">
            <span>Добро пожаловать, {{ authStore.user?.name || 'Пользователь' }}!</span>
            <a-button @click="handleLogout" type="primary" danger>
              Выйти
            </a-button>
          </div>
        </div>
      </a-layout-header>

      <a-layout-content class="content">
        <div class="content-wrapper">
          <!-- Карточки статей -->
          <div class="articles-section">
            <a-row :gutter="[16, 16]">
              <a-col :span="24">
                <div class="section-header">
                  <h2 class="section-title">
                    <FileTextOutlined />
                    Статьи
                  </h2>
                  <a-button type="primary" @click="handleCreateArticle" class="create-article-btn">
                    <PlusOutlined />
                    Создать статью
                  </a-button>
                </div>
                <div class="legend">
                  <a-tag color="green" class="legend-item">
                    <EditOutlined />
                    Ваши статьи
                  </a-tag>
                  <a-tag color="cyan" class="legend-item">
                    <MessageOutlined />
                    Статьи с вашими комментариями
                  </a-tag>
                </div>
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
                  :class="[
                    'article-card',
                    { 'article-card-author': article.is_author },
                    { 'article-card-commented': article.has_commented && !article.is_author }
                  ]"
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
                        <a-tag v-if="article.is_author" color="green" size="small" class="author-tag">
                          Вы
                        </a-tag>
                      </p>
                      <a-tag color="blue" class="comments-tag">
                        <MessageOutlined />
                        {{ article.comments_count }}
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
                  @change="handlePageChange"
                  @show-size-change="handlePageSizeChange"
                />
              </a-col>
            </a-row>
          </div>
        </div>
      </a-layout-content>
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

    <!-- Модальное окно для создания статьи -->
    <CreateArticleModal
      v-model:visible="createModalVisible"
      @article-created="handleArticleCreated"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { message } from 'ant-design-vue'
import { articlesService, type Article } from '../services/articles'
import ArticleModal from '../components/ArticleModal.vue'
import CreateArticleModal from '../components/CreateArticleModal.vue'

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

// Состояние для модального окна создания статьи
const createModalVisible = ref(false)

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
  selectedArticleId.value = articleId
  modalVisible.value = true
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

// Обработка создания статьи
const handleCreateArticle = () => {
  createModalVisible.value = true
}

// Обработка успешного создания статьи
const handleArticleCreated = () => {
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
.dashboard {
  min-height: 100vh;
}

.header {
  background: #001529;
  padding: 0 24px;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 100%;
}

.header-content h1 {
  color: white;
  margin: 0;
  font-size: 20px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 16px;
  color: white;
}

.content {
  padding: 24px;
  background: #f0f2f5;
  min-height: calc(100vh - 64px);
}

.content-wrapper {
  max-width: 1200px;
  margin: 0 auto;
}

/* Стили для секции статей */
.articles-section {
  margin-top: 24px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 16px;
}

.section-title {
  font-size: 24px;
  font-weight: 600;
  color: #262626;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.create-article-btn {
  height: 40px;
  padding: 0 20px;
  font-weight: 500;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 2px 4px rgba(24, 144, 255, 0.2);
  transition: all 0.3s ease;
}

.create-article-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(24, 144, 255, 0.3);
}

.legend {
  display: flex;
  gap: 12px;
  margin-top: 12px;
  flex-wrap: wrap;
}

.legend-item {
  font-size: 13px;
  padding: 4px 12px;
  display: flex;
  align-items: center;
  gap: 6px;
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

/* Зеленоватый фон для статей автора */
.article-card-author {
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border: 2px solid #86efac;
}

.article-card-author:hover {
  background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
  border-color: #4ade80;
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(74, 222, 128, 0.3);
}

/* Голубой фон для статей с комментариями пользователя */
.article-card-commented {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border: 2px solid #7dd3fc;
}

.article-card-commented:hover {
  background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
  border-color: #38bdf8;
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(56, 189, 248, 0.3);
}

/* Обычный стиль для остальных статей */
.article-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.article-card :deep(.ant-card-head) {
  border-bottom: 1px solid #f0f0f0;
  padding: 16px 20px;
}

.article-card-author :deep(.ant-card-head) {
  background: rgba(134, 239, 172, 0.1);
  border-bottom: 1px solid #86efac;
}

.article-card-commented :deep(.ant-card-head) {
  background: rgba(125, 211, 252, 0.1);
  border-bottom: 1px solid #7dd3fc;
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

.author-tag {
  margin-left: 6px;
  font-size: 11px;
  padding: 0 6px;
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

.article-card-author :deep(.ant-card-actions) {
  background: rgba(134, 239, 172, 0.05);
  border-top: 1px solid #86efac;
}

.article-card-commented :deep(.ant-card-actions) {
  background: rgba(125, 211, 252, 0.05);
  border-top: 1px solid #7dd3fc;
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
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .section-title {
    font-size: 20px;
  }

  .create-article-btn {
    width: 100%;
    justify-content: center;
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
