# Articles - Система управления статьями

Laravel + Vue.js приложение для управления статьями с комментариями и системой аутентификации.

## Технологический стек

### Backend
- **Laravel 12** - PHP фреймворк
- **PostgreSQL** - база данных
- **Laravel Sanctum** - аутентификация API
- **Docker** - контейнеризация

### Frontend
- **Vue.js 3** - JavaScript фреймворк
- **TypeScript** - типизация
- **Ant Design Vue** - UI компоненты
- **Pinia** - управление состоянием
- **Vite** - сборщик

## Требования

- Docker и Docker Compose
- Node.js 18+ (для разработки фронтенда)
- Git

## Установка и запуск проекта

### 1. Клонирование репозитория

```bash
git clone <URL_РЕПОЗИТОРИЯ>
cd Articles
```

### 2. Настройка окружения

Создайте файл `.env` на основе `.env.example`:

```bash
cp .env.example .env
```

Отредактируйте файл `.env` и настройте следующие параметры:

```env
APP_NAME="Articles"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=articles
DB_USERNAME=articles_user
DB_PASSWORD=articles_password

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### 3. Запуск контейнеров

Запустите все сервисы через Docker Compose:

```bash
docker-compose up -d
```

Это запустит:
- **app** - PHP-FPM контейнер с Laravel
- **nginx** - веб-сервер (порт 80)
- **db** - PostgreSQL база данных (порт 5432)

### 4. Установка зависимостей PHP

Войдите в контейнер приложения:

```bash
docker-compose exec app bash
```

Установите зависимости Composer:

```bash
composer install
```

### 5. Настройка приложения

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 6. Выполнение миграций

Запустите миграции для создания таблиц в базе данных:

```bash
php artisan migrate
```

### 7. Заполнение базы данных тестовыми данными

Запустите сидеры для создания тестовых пользователей, статей и комментариев:

```bash
php artisan db:seed
```

### 8. Выход из контейнера

```bash
exit
```

## Запуск фронтенда

### 1. Установка зависимостей Node.js

В корневой директории проекта выполните:

```bash
npm install
```

### 2. Запуск в режиме разработки

Запустите Vite dev server:

```bash
npm run dev
```

Фронтенд будет доступен по адресу: `http://localhost:5173`

### 3. Сборка для продакшена

Для создания production сборки:

```bash
npm run build
```

## Доступ к приложению

- **Веб-интерфейс**: http://localhost (через nginx)
- **API документация**: http://localhost/docs (Scribe)
- **Фронтенд (dev)**: http://localhost:5173 (Vite dev server)

## Структура проекта

```
app/
├── Modules/
│   ├── Articles/          # Модуль статей
│   ├── Comments/          # Модуль комментариев
│   ├── Users/             # Модуль пользователей
│   └── Security/          # Модуль безопасности
├── Http/Controllers/      # Контроллеры
└── Rules/                 # Валидационные правила

resources/
├── js/                    # Vue.js приложение
│   ├── components/        # Vue компоненты
│   ├── views/             # Страницы
│   ├── services/          # API сервисы
│   └── stores/            # Pinia хранилища
└── css/                   # Стили

database/
├── migrations/            # Миграции БД
└── seeders/              # Сидеры для тестовых данных
```

## Полезные команды

### Docker

```bash
# Остановить все контейнеры
docker-compose down

# Перезапустить контейнеры
docker-compose restart

# Просмотр логов
docker-compose logs -f app

# Вход в контейнер приложения
docker-compose exec app bash
```

### Laravel

```bash
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Создание нового контроллера
php artisan make:controller ControllerName

# Создание новой миграции
php artisan make:migration create_table_name

# Запуск тестов
php artisan test
```

### Frontend

```bash
# Проверка типов TypeScript
npm run type-check

# Линтинг кода
npm run lint

# Сборка для продакшена
npm run build
```

## Тестирование

Запуск тестов через Docker:

```bash
docker-compose exec app php artisan test
```

## Разработка

### Backend разработка

1. Внесите изменения в PHP код
2. Изменения автоматически отобразятся в контейнере
3. Для применения миграций: `docker-compose exec app php artisan migrate`

### Frontend разработка

1. Запустите `npm run dev` для hot-reload
2. Внесите изменения в Vue/TypeScript код
3. Изменения автоматически пересоберутся

## Устранение неполадок

### Проблемы с правами доступа

```bash
# Установка правильных прав
sudo chown -R $USER:$USER .
chmod -R 755 .
```

### Проблемы с базой данных

```bash
# Пересоздание базы данных
docker-compose down -v
docker-compose up -d
docker-compose exec app php artisan migrate:fresh --seed
```

### Очистка кэша

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## API

API документация доступна по адресу `/docs` после запуска приложения. Используется Scribe для автоматической генерации документации.

## Лицензия

MIT License

