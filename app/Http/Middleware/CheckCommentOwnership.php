<?php

namespace App\Http\Middleware;

use App\Modules\Comments\Persistence\ORM\Comment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCommentOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $commentId = $request->route('id');

        if (! $commentId) {
            return response()->json([
                'message' => __('comments.messages.not_found'),
            ], 404);
        }

        $comment = Comment::find($commentId);

        if (! $comment) {
            return response()->json([
                'message' => __('comments.messages.not_found'),
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

        if ($comment->author_id != $currentUserId) {
            return response()->json([
                'message' => __('comments.messages.forbidden'),
            ], 403);
        }

        // Добавляем комментарий в request для использования в контроллере
        $request->merge(['comment' => $comment]);

        return $next($request);
    }
}
