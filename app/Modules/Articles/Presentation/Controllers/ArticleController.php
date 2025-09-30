<?php

namespace App\Modules\Articles\Presentation\Controllers;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\Actions\DeleteArticleAction;
use App\Modules\Articles\Application\Actions\UpdateArticleAction;
use App\Modules\Articles\Application\DTOs\CreateArticleDTO;
use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Queries\GetArticlesQuery;
use App\Modules\Articles\Presentation\Requests\CreateArticleRequest;
use App\Modules\Articles\Presentation\Requests\UpdateArticleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class ArticleController
{
    public function __construct(
        private GetArticlesQuery $getArticlesQuery,
        private GetArticleQuery $getArticleQuery,
        private CreateArticleAction $createArticleAction,
        private UpdateArticleAction $updateArticleAction,
        private DeleteArticleAction $deleteArticleAction
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        // Получаем ID пользователя из токена, если он есть
        $userId = null;
        if ($request->hasHeader('Authorization')) {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
            $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($personalAccessToken) {
                $userId = $personalAccessToken->tokenable_id;
            }
        }

        $articles = $this->getArticlesQuery->execute($perPage, $userId);

        return response()->json([
            'data' => $articles->items(),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    public function store(CreateArticleRequest $request): JsonResponse
    {
        $dto = CreateArticleDTO::fromArray($request->validated());
        $article = $this->createArticleAction->execute($dto);

        return response()->json([
            'data' => $article->load(['author']),
            'message' => __('articles::messages.created'),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $article = $this->getArticleQuery->execute($id);

        return response()->json([
            'data' => $article,
        ]);
    }

    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $article = \App\Modules\Articles\Persistence\ORM\Article::findOrFail($id);

        // Проверяем, что пользователь является автором статьи
        if ($request->user()->id !== $article->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dto = UpdateArticleDTO::fromArray($request->validated());
        $updatedArticle = $this->updateArticleAction->execute($article, $dto);

        return response()->json([
            'data' => $updatedArticle->load(['author']),
            'message' => __('articles::messages.updated'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $article = \App\Modules\Articles\Persistence\ORM\Article::findOrFail($id);

        // Проверяем, что пользователь является автором статьи
        if (request()->user()->id !== $article->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->deleteArticleAction->execute($article);

        return response()->json([
            'message' => __('articles::messages.deleted'),
        ]);
    }
}
