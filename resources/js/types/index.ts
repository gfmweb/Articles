// Базовые типы для приложения

export interface User {
    id: number
    name: string
    email: string
    created_at: string
    updated_at: string
}

export interface Article {
    id: number
    title: string
    content: string
    author_id: number
    created_at: string
    updated_at: string
}

export interface Comment {
    id: number
    content: string
    article_id: number
    user_id: number
    created_at: string
    updated_at: string
}

export interface ApiResponse<T> {
    data: T
    message?: string
    status: number
}
