<?php

namespace App\Http\Middleware;

use App\Modules\Articles\Persistence\ORM\Article;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckArticleOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $articleId = $request->route('article') ?? $request->route('id');

        // Если articleId - это объект Article, получаем ID
        if (is_object($articleId)) {
            $articleId = $articleId->id;
        }

        if (! $articleId) {
            return response()->json([
                'message' => __('articles.messages.not_found'),
            ], 404);
        }

        $article = Article::find($articleId);

        if (! $article) {
            return response()->json([
                'message' => __('articles.messages.not_found'),
            ], 404);
        }

        // Получаем ID текущего пользователя из токена
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => __('auth.unauthorized'),
            ], 401);
        }

        $currentUserId = $user->id;

        if ($article->author_id != $currentUserId) {
            return response()->json([
                'message' => __('articles.messages.forbidden'),
            ], 403);
        }

        // Добавляем статью в request для использования в контроллере
        $request->merge(['article' => $article]);

        return $next($request);
    }
}
