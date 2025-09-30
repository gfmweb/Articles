# Articles API

Laravel API приложение для управления статьями с Vue.js фронтендом.

## Требования

- PHP 8.1+
- Composer
- Node.js 18+
- Docker и Docker Compose
- MySQL 8.0+

## Установка и запуск проекта

### 1. Клонирование репозитория

```bash
git clone <repository-url>
cd Articles
```

### 2. Установка зависимостей

#### Backend (Laravel)
```bash
composer install
```

#### Frontend (Vue.js)
```bash
npm install
```

### 3. Настройка окружения

Скопируйте файл конфигурации окружения:
```bash
cp .env.example .env
```

Отредактируйте файл `.env` и настройте следующие параметры:
```env
APP_NAME="Articles API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=articles
DB_USERNAME=root
DB_PASSWORD=

# Настройки для Docker
DB_HOST=mysql
DB_PORT=3306
```

### 4. Генерация ключа приложения

```bash
php artisan key:generate
```

### 5. Запуск через Docker

#### Запуск контейнеров
```bash
docker-compose up -d
```

#### Выполнение команд внутри контейнера
```bash
# Войти в контейнер PHP
docker-compose exec app bash

# Или выполнить команды напрямую
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### 6. Выполнение миграций

```bash
# Если используете Docker
docker-compose exec app php artisan migrate

# Если запускаете локально
php artisan migrate
```

### 7. Заполнение базы данных тестовыми данными

```bash
# Если используете Docker
docker-compose exec app php artisan db:seed

# Если запускаете локально
php artisan db:seed
```

### 8. Запуск фронтенда

#### Установка зависимостей (если еще не установлены)
```bash
npm install
```

#### Запуск в режиме разработки
```bash
npm run dev
```

Фронтенд будет доступен по адресу: `http://localhost:5173`

#### Сборка для продакшена
```bash
npm run build
```

## Доступные сервисы

После запуска `docker-compose up -d` будут доступны:

- **API Backend**: http://localhost:8000
- **Frontend**: http://localhost:5173 (при запуске `npm run dev`)
- **MySQL**: localhost:3306
- **Nginx**: http://localhost:80

## Структура проекта

```
Articles/
├── app/                    # Laravel приложение
│   ├── Modules/           # Модульная архитектура
│   │   ├── Articles/      # Модуль статей
│   │   ├── Comments/      # Модуль комментариев
│   │   └── Users/         # Модуль пользователей
│   └── ...
├── resources/
│   ├── js/                # Vue.js фронтенд
│   │   ├── components/    # Vue компоненты
│   │   ├── views/         # Страницы
│   │   └── services/      # API сервисы
│   └── ...
├── tests/                 # Тесты
└── ...
```

## API Документация

После запуска приложения API документация доступна по адресу:
- http://localhost:8000/docs (если используется Scribe)

## Тестирование

### Запуск тестов

```bash
# Если используете Docker
docker-compose exec app php artisan test

# Если запускаете локально
php artisan test
```

### Запуск конкретных тестов

```bash
# Тесты модуля Articles
docker-compose exec app php artisan test tests/Modules/Articles/

# Тесты модуля Users
docker-compose exec app php artisan test tests/Modules/Users/

# Тесты модуля Comments
docker-compose exec app php artisan test tests/Modules/Comments/
```

## Разработка

### Генерация кода

```bash
# Создание нового модуля
php artisan make:module ModuleName

# Создание контроллера
php artisan make:controller ControllerName

# Создание модели
php artisan make:model ModelName -m
```

### Линтинг и форматирование

```bash
# PHP Code Style
./vendor/bin/pint

# PHPStan анализ
./vendor/bin/phpstan analyse
```

## Полезные команды

```bash
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Пересоздание базы данных
php artisan migrate:fresh --seed

# Запуск очереди (если используется)
php artisan queue:work
```

## Устранение неполадок

### Проблемы с правами доступа
```bash
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Проблемы с Docker
```bash
# Пересоздание контейнеров
docker-compose down
docker-compose up -d --build

# Очистка Docker кэша
docker system prune -a
```

### Проблемы с Node.js
```bash
# Очистка кэша npm
npm cache clean --force

# Удаление node_modules и переустановка
rm -rf node_modules package-lock.json
npm install
```
