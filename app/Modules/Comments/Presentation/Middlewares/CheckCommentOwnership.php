<?php

namespace App\Modules\Comments\Presentation\Middlewares;

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
                'message' => __('comments::messages.not_found'),
            ], 404);
        }

        $comment = Comment::find($commentId);

        if (! $comment) {
            return response()->json([
                'message' => __('comments::messages.not_found'),
            ], 404);
        }

        // User is guaranteed by auth:sanctum middleware
        $user = $request->user();

        if ($comment->author_id !== $user->id) {
            return response()->json([
                'message' => __('comments::messages.forbidden'),
            ], 403);
        }

        // Добавляем комментарий в request для использования в контроллере
        $request->merge(['comment' => $comment]);

        return $next($request);
    }
}
