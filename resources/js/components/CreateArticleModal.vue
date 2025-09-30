<template>
  <a-modal
    :open="visible"
    title="Создать статью"
    width="600px"
    :footer="null"
    @cancel="handleClose"
    class="create-article-modal"
  >
    <a-form
      :model="form"
      :rules="rules"
      layout="vertical"
      ref="formRef"
      @finish="handleSubmit"
    >
      <a-form-item label="Заголовок" name="title">
        <a-input
          v-model:value="form.title"
          placeholder="Введите заголовок статьи"
          :maxlength="255"
          show-count
        />
      </a-form-item>

      <a-form-item label="Содержание" name="content">
        <a-textarea
          v-model:value="form.content"
          placeholder="Введите содержание статьи"
          :rows="8"
          :maxlength="5000"
          show-count
        />
      </a-form-item>

      <a-form-item>
        <a-space>
          <a-button
            type="primary"
            html-type="submit"
            :loading="saving"
            :disabled="!form.title.trim() || !form.content.trim()"
          >
            <SaveOutlined />
            Сохранить
          </a-button>
          <a-button @click="handleClose">
            <CloseOutlined />
            Отменить
          </a-button>
        </a-space>
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { message } from 'ant-design-vue'
import { articlesService } from '../services/articles'

interface Props {
  visible: boolean
}

interface Emits {
  (e: 'update:visible', value: boolean): void
  (e: 'article-created'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Состояние формы
const formRef = ref()
const saving = ref(false)

const form = reactive({
  title: '',
  content: ''
})

// Правила валидации
const rules = {
  title: [
    { required: true, message: 'Пожалуйста, введите заголовок статьи', trigger: 'blur' },
    { min: 3, message: 'Заголовок должен содержать минимум 3 символа', trigger: 'blur' },
    { max: 255, message: 'Заголовок не должен превышать 255 символов', trigger: 'blur' }
  ],
  content: [
    { required: true, message: 'Пожалуйста, введите содержание статьи', trigger: 'blur' },
    { min: 10, message: 'Содержание должно содержать минимум 10 символов', trigger: 'blur' },
    { max: 5000, message: 'Содержание не должно превышать 5000 символов', trigger: 'blur' }
  ]
}

// Методы
const handleClose = () => {
  // Сбрасываем форму
  form.title = ''
  form.content = ''
  formRef.value?.resetFields()
  emit('update:visible', false)
}

const handleSubmit = async () => {
  try {
    saving.value = true
    await articlesService.createArticle({
      title: form.title.trim(),
      content: form.content.trim()
    })

    message.success('Статья успешно создана')
    emit('article-created')
    handleClose()
  } catch (error) {
    console.error('Ошибка создания статьи:', error)
    message.error('Не удалось создать статью')
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.create-article-modal :deep(.ant-modal-body) {
  padding: 24px;
}

.create-article-modal :deep(.ant-form-item) {
  margin-bottom: 20px;
}

.create-article-modal :deep(.ant-form-item:last-child) {
  margin-bottom: 0;
}

.create-article-modal :deep(.ant-input),
.create-article-modal :deep(.ant-input:focus),
.create-article-modal :deep(.ant-input-focused) {
  border-radius: 6px;
}

.create-article-modal :deep(.ant-input-affix-wrapper) {
  border-radius: 6px;
}

.create-article-modal :deep(.ant-input-affix-wrapper:focus),
.create-article-modal :deep(.ant-input-affix-wrapper-focused) {
  border-radius: 6px;
}

.create-article-modal :deep(.ant-btn) {
  border-radius: 6px;
  height: 36px;
  padding: 0 16px;
  font-weight: 500;
}

.create-article-modal :deep(.ant-btn-primary) {
  background: #1890ff;
  border-color: #1890ff;
}

.create-article-modal :deep(.ant-btn-primary:hover) {
  background: #40a9ff;
  border-color: #40a9ff;
}

.create-article-modal :deep(.ant-form-item-label > label) {
  font-weight: 600;
  color: #262626;
}

.create-article-modal :deep(.ant-input) {
  font-size: 14px;
}

.create-article-modal :deep(.ant-input::placeholder) {
  color: #bfbfbf;
}

.create-article-modal :deep(.ant-input-count) {
  color: #8c8c8c;
  font-size: 12px;
}

/* Адаптивность */
@media (max-width: 768px) {
  .create-article-modal :deep(.ant-modal) {
    margin: 0;
    max-width: 100vw;
    top: 0;
    padding-bottom: 0;
  }

  .create-article-modal :deep(.ant-modal-body) {
    padding: 16px;
  }

  .create-article-modal :deep(.ant-space) {
    width: 100%;
    justify-content: center;
  }

  .create-article-modal :deep(.ant-btn) {
    flex: 1;
    max-width: 120px;
  }
}
</style>
