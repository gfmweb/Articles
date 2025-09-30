<template>
  <a-modal
    :open="visible"
    :title="article?.title || 'Загрузка...'"
    width="800px"
    :footer="null"
    @cancel="handleClose"
    class="article-modal"
  >
    <div v-if="loading" class="loading-container">
      <a-spin size="large">
        <template #indicator>
          <LoadingOutlined style="font-size: 24px" spin />
        </template>
        Загрузка статьи...
      </a-spin>
    </div>

    <div v-else-if="article" class="article-content">
      <!-- Заголовок статьи -->
      <div class="article-header">
        <h1 class="article-title">{{ article.title }}</h1>
        <div class="article-meta">
          <div class="article-author">
            <UserOutlined />
            <span>{{ article.author.name }}</span>
            <a-tag v-if="isAuthor" color="green" size="small" class="author-tag">
              Вы
            </a-tag>
          </div>
          <div class="article-date">
            <CalendarOutlined />
            <span>{{ formatDate(article.created_at) }}</span>
          </div>
          <div class="article-stats">
            <a-tag color="blue">
              <MessageOutlined />
              {{ article.comments_count || 0 }} комментариев
            </a-tag>
          </div>
        </div>
      </div>

      <!-- Кнопки действий для автора -->
      <div v-if="isAuthor" class="article-actions">
        <a-space>
          <a-button type="primary" @click="handleEdit">
            <EditOutlined />
            Редактировать
          </a-button>
          <a-button danger @click="handleDelete">
            <DeleteOutlined />
            Удалить
          </a-button>
        </a-space>
      </div>

      <!-- Режим редактирования -->
      <div v-if="isEditing" class="edit-mode">
        <a-form :model="editForm" layout="vertical">
          <a-form-item label="Заголовок">
            <a-input v-model:value="editForm.title" placeholder="Введите заголовок статьи" />
          </a-form-item>
          <a-form-item label="Содержание">
            <a-textarea
              v-model:value="editForm.content"
              placeholder="Введите содержание статьи"
              :rows="8"
            />
          </a-form-item>
          <a-form-item>
            <a-space>
              <a-button type="primary" @click="handleSave" :loading="saving">
                <SaveOutlined />
                Сохранить
              </a-button>
              <a-button @click="handleCancelEdit">
                <CloseOutlined />
                Отменить
              </a-button>
            </a-space>
          </a-form-item>
        </a-form>
      </div>

      <!-- Содержание статьи -->
      <div v-else class="article-body">
        <div class="article-text" v-html="formatContent(article.content)"></div>
      </div>

      <!-- Комментарии -->
      <div v-if="!isEditing" class="comments-section">
        <a-divider>
          <MessageOutlined />
          Комментарии ({{ comments.length || 0 }})
        </a-divider>

        <!-- Форма добавления комментария (только для неавторов) -->
        <div v-if="!isAuthor" class="add-comment-section">
          <a-form :model="newCommentForm" layout="vertical">
            <a-form-item>
              <a-textarea
                v-model:value="newCommentForm.text"
                placeholder="Напишите ваш комментарий..."
                :rows="3"
                :maxlength="1000"
                show-count
                class="comment-textarea"
              />
            </a-form-item>
            <a-form-item>
              <a-space>
                <a-button
                  type="primary"
                  @click="handleAddComment"
                  :loading="addingComment"
                  :disabled="!newCommentForm.text.trim()"
                >
                  <MessageOutlined />
                  Добавить комментарий
                </a-button>
                <a-button @click="handleCancelComment">
                  Отмена
                </a-button>
              </a-space>
            </a-form-item>
          </a-form>
        </div>

        <div v-if="commentsLoading" class="comments-loading">
          <a-spin size="small">
            <template #indicator>
              <LoadingOutlined style="font-size: 16px" spin />
            </template>
            Загрузка комментариев...
          </a-spin>
        </div>

        <div v-else-if="comments.length === 0" class="no-comments">
          <a-empty description="Пока нет комментариев" />
        </div>

        <div v-else class="comments-list">
          <div
            v-for="comment in comments"
            :key="comment.id"
            class="comment-item"
          >
            <div class="comment-header">
              <div class="comment-author">
                <UserOutlined />
                <span>{{ comment.author.name }}</span>
                <a-tag v-if="isCommentAuthor(comment)" color="green" size="small" class="author-tag">
                  Вы
                </a-tag>
              </div>
              <div class="comment-date">
                <CalendarOutlined />
                <span>{{ formatDate(comment.created_at) }}</span>
              </div>
            </div>

            <!-- Режим редактирования комментария -->
            <div v-if="editingCommentId === comment.id" class="comment-edit-mode">
              <a-textarea
                v-model:value="editingCommentText"
                placeholder="Введите текст комментария"
                :rows="3"
                class="comment-edit-textarea"
              />
              <div class="comment-edit-actions">
                <a-space>
                  <a-button type="primary" size="small" @click="handleSaveComment(comment.id)" :loading="savingComment">
                    <SaveOutlined />
                    Сохранить
                  </a-button>
                  <a-button size="small" @click="handleCancelCommentEdit">
                    <CloseOutlined />
                    Отменить
                  </a-button>
                </a-space>
              </div>
            </div>

            <!-- Обычный режим отображения комментария -->
            <div v-else class="comment-content">
              {{ comment.text }}
            </div>

            <!-- Кнопки действий для автора комментария -->
            <div v-if="isCommentAuthor(comment) && editingCommentId !== comment.id" class="comment-actions">
              <a-space>
                <a-button type="link" size="small" @click="handleEditComment(comment)">
                  <EditOutlined />
                  Редактировать
                </a-button>
                <a-button type="link" size="small" danger @click="handleDeleteComment(comment.id)">
                  <DeleteOutlined />
                  Удалить
                </a-button>
              </a-space>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="error-container">
      <a-result
        status="error"
        title="Ошибка загрузки"
        sub-title="Не удалось загрузить статью"
      >
        <template #extra>
          <a-button type="primary" @click="handleClose">
            Закрыть
          </a-button>
        </template>
      </a-result>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { message, Modal } from 'ant-design-vue'
import { articlesService, type Article } from '../services/articles'
import { commentsService, type Comment } from '../services/comments'

interface Props {
  articleId: number | null
  visible: boolean
  currentUserId?: number
}

interface Emits {
  (e: 'update:visible', value: boolean): void
  (e: 'article-updated'): void
  (e: 'article-deleted'): void
  (e: 'comment-deleted'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Состояние
const article = ref<Article | null>(null)
const comments = ref<Comment[]>([])
const loading = ref(false)
const commentsLoading = ref(false)
const isEditing = ref(false)
const saving = ref(false)

// Состояние для редактирования комментариев
const editingCommentId = ref<number | null>(null)
const editingCommentText = ref('')
const savingComment = ref(false)

// Состояние для добавления комментария
const addingComment = ref(false)
const newCommentForm = ref({
  text: ''
})

// Форма редактирования
const editForm = ref({
  title: '',
  content: ''
})

// Вычисляемые свойства
const isAuthor = computed(() => {
  return article.value && props.currentUserId && article.value.author_id === props.currentUserId
})

// Методы
const loadArticle = async () => {
  if (!props.articleId) return

  try {
    loading.value = true
    const response = await articlesService.getArticle(props.articleId)
    article.value = response.data
    editForm.value = {
      title: article.value.title,
      content: article.value.content
    }
  } catch (error) {
    console.error('Ошибка загрузки статьи:', error)
    message.error('Не удалось загрузить статью')
  } finally {
    loading.value = false
  }
}

const loadComments = async () => {
  if (!props.articleId) return

  try {
    commentsLoading.value = true
    const response = await commentsService.getComments(props.articleId)
    comments.value = response.data
  } catch (error) {
    console.error('Ошибка загрузки комментариев:', error)
    message.error('Не удалось загрузить комментарии')
  } finally {
    commentsLoading.value = false
  }
}

const handleClose = () => {
  emit('update:visible', false)
  isEditing.value = false
}

const handleEdit = () => {
  isEditing.value = true
}

const handleCancelEdit = () => {
  isEditing.value = false
  if (article.value) {
    editForm.value = {
      title: article.value.title,
      content: article.value.content
    }
  }
}

const handleSave = async () => {
  if (!article.value) return

  try {
    saving.value = true
    await articlesService.updateArticle(article.value.id, editForm.value)
    message.success('Статья успешно обновлена')
    isEditing.value = false
    await loadArticle() // Перезагружаем статью
    emit('article-updated')
  } catch (error) {
    console.error('Ошибка обновления статьи:', error)
    message.error('Не удалось обновить статью')
  } finally {
    saving.value = false
  }
}

const handleDelete = () => {
  if (!article.value) return

  // Показываем подтверждение удаления
  const modal = Modal.confirm({
    title: 'Подтверждение удаления',
    content: 'Вы уверены, что хотите удалить эту статью? Это действие нельзя отменить.',
    okText: 'Удалить',
    okType: 'danger',
    cancelText: 'Отмена',
    onOk: async () => {
      try {
        await articlesService.deleteArticle(article.value!.id)
        message.success('Статья успешно удалена')
        emit('article-deleted')
        handleClose()
      } catch (error) {
        console.error('Ошибка удаления статьи:', error)
        message.error('Не удалось удалить статью')
      }
    }
  })
}

// Методы для работы с комментариями
const isCommentAuthor = (comment: Comment) => {
  return props.currentUserId && comment.author_id === props.currentUserId
}

const handleEditComment = (comment: Comment) => {
  editingCommentId.value = comment.id
  editingCommentText.value = comment.text
}

const handleCancelCommentEdit = () => {
  editingCommentId.value = null
  editingCommentText.value = ''
}

const handleSaveComment = async (commentId: number) => {
  if (!editingCommentText.value.trim()) {
    message.warning('Текст комментария не может быть пустым')
    return
  }

  try {
    savingComment.value = true
    await commentsService.updateComment(commentId, editingCommentText.value)
    message.success('Комментарий успешно обновлен')

    // Обновляем комментарий в списке
    const commentIndex = comments.value.findIndex(c => c.id === commentId)
    if (commentIndex !== -1) {
      comments.value[commentIndex].text = editingCommentText.value
    }

    handleCancelCommentEdit()
  } catch (error) {
    console.error('Ошибка обновления комментария:', error)
    message.error('Не удалось обновить комментарий')
  } finally {
    savingComment.value = false
  }
}

const handleDeleteComment = (commentId: number) => {
  Modal.confirm({
    title: 'Подтверждение удаления',
    content: 'Вы уверены, что хотите удалить этот комментарий?',
    okText: 'Удалить',
    okType: 'danger',
    cancelText: 'Отмена',
    onOk: async () => {
      try {
        await commentsService.deleteComment(commentId)
        message.success('Комментарий успешно удален')

        // Удаляем комментарий из списка
        const commentIndex = comments.value.findIndex(c => c.id === commentId)
        if (commentIndex !== -1) {
          comments.value.splice(commentIndex, 1)
        }

        // Обновляем счетчик комментариев в статье
        if (article.value) {
          article.value.comments_count = Math.max(0, (article.value.comments_count || 0) - 1)
        }

        // Эмитим событие для обновления списка статей
        emit('comment-deleted')
      } catch (error) {
        console.error('Ошибка удаления комментария:', error)
        message.error('Не удалось удалить комментарий')
      }
    }
  })
}

// Методы для добавления комментария
const handleAddComment = async () => {
  if (!props.articleId || !newCommentForm.value.text.trim()) {
    message.warning('Введите текст комментария')
    return
  }

  try {
    addingComment.value = true
    const response = await commentsService.createComment(props.articleId, newCommentForm.value.text)

    // Добавляем новый комментарий в список
    comments.value.push(response.data)

    // Обновляем счетчик комментариев в статье
    if (article.value) {
      article.value.comments_count = (article.value.comments_count || 0) + 1
    }

    // Очищаем форму
    newCommentForm.value.text = ''

    message.success('Комментарий успешно добавлен')

    // Эмитим событие для обновления списка статей
    emit('comment-deleted')
  } catch (error) {
    console.error('Ошибка добавления комментария:', error)
    message.error('Не удалось добавить комментарий')
  } finally {
    addingComment.value = false
  }
}

const handleCancelComment = () => {
  newCommentForm.value.text = ''
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatContent = (content: string) => {
  // Простое форматирование текста - замена переносов строк на <br>
  return content.replace(/\n/g, '<br>')
}

// Наблюдатели
watch(() => props.articleId, (newId) => {
  if (newId && props.visible) {
    loadArticle()
    loadComments()
  }
})

watch(() => props.visible, (newVisible) => {
  if (newVisible && props.articleId) {
    loadArticle()
    loadComments()
  }
})
</script>

<style scoped>
.article-modal :deep(.ant-modal-body) {
  max-height: 70vh;
  overflow-y: auto;
  padding: 24px;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
}

.article-content {
  max-width: 100%;
}

.article-header {
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #f0f0f0;
}

.article-title {
  font-size: 24px;
  font-weight: 600;
  color: #262626;
  margin: 0 0 16px 0;
  line-height: 1.4;
}

.article-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  align-items: center;
  font-size: 14px;
  color: #8c8c8c;
}

.article-author,
.article-date {
  display: flex;
  align-items: center;
  gap: 4px;
}

.author-tag {
  margin-left: 8px;
  font-size: 11px;
  padding: 0 6px;
}

.article-actions {
  margin-bottom: 24px;
  padding: 16px;
  background: #fafafa;
  border-radius: 6px;
  border: 1px solid #f0f0f0;
}

.edit-mode {
  margin-bottom: 24px;
}

.article-body {
  margin-bottom: 24px;
}

.article-text {
  font-size: 16px;
  line-height: 1.6;
  color: #262626;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.comments-section {
  margin-top: 24px;
}

.add-comment-section {
  margin-bottom: 24px;
  padding: 16px;
  background: #fafafa;
  border-radius: 6px;
  border: 1px solid #f0f0f0;
}

.comment-textarea {
  resize: vertical;
  min-height: 80px;
}

.comments-loading {
  display: flex;
  justify-content: center;
  padding: 20px;
}

.no-comments {
  text-align: center;
  padding: 40px 20px;
}

.comments-list {
  max-height: 300px;
  overflow-y: auto;
}

.comment-item {
  margin-bottom: 16px;
  padding: 12px;
  background: #fafafa;
  border-radius: 6px;
  border: 1px solid #f0f0f0;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
  font-size: 12px;
  color: #8c8c8c;
}

.comment-author,
.comment-date {
  display: flex;
  align-items: center;
  gap: 4px;
}

.comment-content {
  font-size: 14px;
  line-height: 1.5;
  color: #262626;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.comment-actions {
  margin-top: 8px;
  padding-top: 8px;
  border-top: 1px solid #f0f0f0;
}

.comment-edit-mode {
  margin-top: 8px;
}

.comment-edit-textarea {
  margin-bottom: 8px;
}

.comment-edit-actions {
  display: flex;
  justify-content: flex-end;
}

.error-container {
  text-align: center;
  padding: 40px 20px;
}

/* Адаптивность */
@media (max-width: 768px) {
  .article-modal :deep(.ant-modal) {
    margin: 0;
    max-width: 100vw;
    top: 0;
    padding-bottom: 0;
  }

  .article-modal :deep(.ant-modal-body) {
    max-height: calc(100vh - 100px);
  }

  .article-meta {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }

  .article-actions {
    padding: 12px;
  }

  .article-actions .ant-space {
    width: 100%;
    justify-content: center;
  }
}
</style>
