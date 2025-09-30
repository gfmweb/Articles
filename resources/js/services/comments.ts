import api from './api'

export interface Comment {
    id: number
    text: string
    article_id: number
    author_id: number
    created_at: string
    updated_at: string
    author: {
        id: number
        name: string
        email: string
    }
}

export interface CommentsResponse {
    data: Comment[]
}

export const commentsService = {
    async getComments(articleId: number): Promise<CommentsResponse> {
        const response = await api.get(`/articles/${articleId}/comments`)
        return response.data
    },

    async createComment(articleId: number, text: string): Promise<{ data: Comment }> {
        const response = await api.post(`/articles/${articleId}/comments`, { text })
        return response.data
    },

    async updateComment(commentId: number, text: string): Promise<{ data: Comment }> {
        const response = await api.put(`/comments/${commentId}`, { text })
        return response.data
    },

    async deleteComment(commentId: number): Promise<void> {
        await api.delete(`/comments/${commentId}`)
    }
}
