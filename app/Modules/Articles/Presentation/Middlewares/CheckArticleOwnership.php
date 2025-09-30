<?php

namespace App\Modules\Articles\Presentation\Middlewares;

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

        if (is_object($articleId)) {
            $articleId = $articleId->id;
        }

        if (! $articleId) {
            return response()->json([
                'message' => __('articles::messages.not_found'),
            ], 404);
        }

        $article = Article::find($articleId);

        if (! $article) {
            return response()->json([
                'message' => __('articles::messages.not_found'),
            ], 404);
        }

        $user = $request->user();
        if ($article->author_id !== $user->id) {
            return response()->json([
                'message' => __('articles::messages.forbidden'),
            ], 403);
        }
        $request->merge(['article' => $article]);

        return $next($request);
    }
}
