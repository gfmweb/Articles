import api from './api'

export interface Article {
    id: number
    title: string
    content: string
    author_id: number
    created_at: string
    updated_at: string
    author: {
        id: number
        name: string
        email: string
    }
    comments_count: number
    is_author?: boolean
    has_commented?: boolean
}

export interface ArticlesResponse {
    data: Article[]
    meta: {
        current_page: number
        last_page: number
        per_page: number
        total: number
    }
}

export const articlesService = {
    async getArticles(page: number = 1, perPage: number = 15): Promise<ArticlesResponse> {
        const response = await api.get('/articles', {
            params: { page, per_page: perPage }
        })
        return response.data
    },

    async getArticle(id: number): Promise<{ data: Article }> {
        const response = await api.get(`/articles/${id}`)
        return response.data
    },

    async updateArticle(id: number, data: { title: string; content: string }): Promise<{ data: Article }> {
        const response = await api.put(`/articles/${id}`, data)
        return response.data
    },

    async deleteArticle(id: number): Promise<void> {
        await api.delete(`/articles/${id}`)
    }
}
