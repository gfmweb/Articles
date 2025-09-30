<?php

namespace App\Modules\Articles\Presentation\Controllers;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\Actions\DeleteArticleAction;
use App\Modules\Articles\Application\Actions\UpdateArticleAction;
use App\Modules\Articles\Application\DTOs\CreateArticleDTO;
use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Queries\GetArticlesQuery;
use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Articles\Presentation\Requests\CreateArticleRequest;
use App\Modules\Articles\Presentation\Requests\UpdateArticleRequest;
use App\Modules\Articles\Presentation\Resources\ArticleCollection;
use App\Modules\Articles\Presentation\Resources\ArticleResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class ArticleController
{
    use AuthorizesRequests;

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
        $userId = $request->user()?->id;

        // Если пользователь не аутентифицирован через middleware, попробуем получить из токена
        if (!$userId && $request->bearerToken()) {
            try {
                $user = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken())?->tokenable;
                $userId = $user?->id;
            } catch (\Exception $e) {
                // Игнорируем ошибки
            }
        }


        $articles = $this->getArticlesQuery->execute($perPage, $userId);

        return (new ArticleCollection($articles))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateArticleRequest $request): JsonResponse
    {
        $dto = CreateArticleDTO::fromArray($request->validated());
        $article = $this->createArticleAction->execute($dto);
        $article->load(['author']);

        // Добавляем поля для подсветки
        $article->setAttribute('is_author', true);
        $article->setAttribute('has_commented', false);

        return (new ArticleResource($article))
            ->additional(['message' => __('articles::messages.created')])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()?->id;
        $article = $this->getArticleQuery->execute($id, $userId);

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(200);
    }

    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $userId = $request->user()?->id;

        // Если пользователь не аутентифицирован через middleware, попробуем получить из токена
        if (!$userId && $request->bearerToken()) {
            try {
                $user = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken())?->tokenable;
                $userId = $user?->id;
            } catch (\Exception $e) {
                // Игнорируем ошибки
            }
        }

        // Получаем статью по ID
        $article = $this->getArticleQuery->execute($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        // Проверяем, что пользователь авторизован и является автором статьи
        if (!$userId || $article->author_id !== $userId) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $dto = UpdateArticleDTO::fromArray($request->validated());
        $updatedArticle = $this->updateArticleAction->execute($article, $dto);
        $updatedArticle->load(['author']);

        // Добавляем поля для подсветки
        $updatedArticle->setAttribute('is_author', true);
        $updatedArticle->setAttribute('has_commented', false);

        return (new ArticleResource($updatedArticle))
            ->additional(['message' => __('articles::messages.updated')])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()?->id;

        // Если пользователь не аутентифицирован через middleware, попробуем получить из токена
        if (!$userId && $request->bearerToken()) {
            try {
                $user = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken())?->tokenable;
                $userId = $user?->id;
            } catch (\Exception $e) {
                // Игнорируем ошибки
            }
        }

        // Получаем статью по ID
        $article = $this->getArticleQuery->execute($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        // Проверяем, что пользователь авторизован и является автором статьи
        if (!$userId || $article->author_id !== $userId) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $this->deleteArticleAction->execute($article);

        return response()->json([
            'message' => __('articles::messages.deleted'),
        ]);
    }
}
