<template>
  <div class="icon-demo">
    <a-card title="Демонстрация иконок Ant Design" class="demo-card">
      <a-row :gutter="[16, 16]">
        <a-col :span="24">
          <h3>Основные иконки</h3>
          <a-space wrap>
            <a-button>
              <HomeOutlined />
              Главная
            </a-button>
            <a-button>
              <UserOutlined />
              Пользователь
            </a-button>
            <a-button>
              <SettingOutlined />
              Настройки
            </a-button>
            <a-button>
              <SearchOutlined />
              Поиск
            </a-button>
            <a-button>
              <PlusOutlined />
              Добавить
            </a-button>
            <a-button>
              <EditOutlined />
              Редактировать
            </a-button>
            <a-button>
              <DeleteOutlined />
              Удалить
            </a-button>
          </a-space>
        </a-col>

        <a-col :span="24">
          <h3>Иконки действий</h3>
          <a-space wrap>
            <a-button type="primary">
              <SaveOutlined />
              Сохранить
            </a-button>
            <a-button type="default">
              <DownloadOutlined />
              Скачать
            </a-button>
            <a-button type="dashed">
              <UploadOutlined />
              Загрузить
            </a-button>
            <a-button type="link">
              <LinkOutlined />
              Ссылка
            </a-button>
            <a-button type="text">
              <CopyOutlined />
              Копировать
            </a-button>
          </a-space>
        </a-col>

        <a-col :span="24">
          <h3>Иконки статуса</h3>
          <a-space wrap>
            <a-tag color="success">
              <CheckCircleOutlined />
              Успешно
            </a-tag>
            <a-tag color="error">
              <CloseCircleOutlined />
              Ошибка
            </a-tag>
            <a-tag color="warning">
              <ExclamationCircleOutlined />
              Предупреждение
            </a-tag>
            <a-tag color="processing">
              <LoadingOutlined />
              Загрузка
            </a-tag>
            <a-tag color="default">
              <QuestionCircleOutlined />
              Вопрос
            </a-tag>
          </a-space>
        </a-col>

        <a-col :span="24">
          <h3>Иконки навигации</h3>
          <a-space wrap>
            <a-button>
              <LeftOutlined />
              Назад
            </a-button>
            <a-button>
              <RightOutlined />
              Вперед
            </a-button>
            <a-button>
              <UpOutlined />
              Вверх
            </a-button>
            <a-button>
              <DownOutlined />
              Вниз
            </a-button>
            <a-button>
              <MenuOutlined />
              Меню
            </a-button>
            <a-button>
              <BarsOutlined />
              Список
            </a-button>
          </a-space>
        </a-col>

        <a-col :span="24">
          <h3>Иконки социальных сетей</h3>
          <a-space wrap>
            <a-button>
              <GithubOutlined />
              GitHub
            </a-button>
            <a-button>
              <TwitterOutlined />
              Twitter
            </a-button>
            <a-button>
              <FacebookOutlined />
              Facebook
            </a-button>
            <a-button>
              <LinkedinOutlined />
              LinkedIn
            </a-button>
            <a-button>
              <InstagramOutlined />
              Instagram
            </a-button>
          </a-space>
        </a-col>

        <a-col :span="24">
          <h3>Иконки с анимацией</h3>
          <a-space wrap>
            <a-button>
              <LoadingOutlined :spin="true" />
              Загрузка
            </a-button>
            <a-button>
              <SyncOutlined :spin="true" />
              Синхронизация
            </a-button>
            <a-button>
              <ReloadOutlined :spin="true" />
              Обновить
            </a-button>
          </a-space>
        </a-col>
      </a-row>
    </a-card>
  </div>
</template>

<script setup lang="ts">
// Компонент для демонстрации различных иконок Ant Design
</script>

<style scoped>
.icon-demo {
  margin: 24px 0;
}

.demo-card {
  margin-bottom: 24px;
}

.demo-card h3 {
  margin-bottom: 16px;
  color: #1890ff;
  font-weight: 600;
}
</style>
