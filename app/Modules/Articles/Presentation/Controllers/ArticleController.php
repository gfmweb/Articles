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

class ArticleController
{
    public function __construct(
        private GetArticlesQuery $getArticlesQuery,
        private GetArticleQuery $getArticleQuery,
        private CreateArticleAction $createArticleAction,
        private UpdateArticleAction $updateArticleAction,
        private DeleteArticleAction $deleteArticleAction
    ) {}

    /**
     * Display a listing of articles.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $articles = $this->getArticlesQuery->execute($perPage);

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

    /**
     * Store a newly created article.
     */
    public function store(CreateArticleRequest $request): JsonResponse
    {
        $dto = CreateArticleDTO::fromArray($request->validated());
        $article = $this->createArticleAction->execute($dto);

        return response()->json([
            'data' => $article->load(['author']),
            'message' => __('articles.messages.created'),
        ], 201);
    }

    /**
     * Display the specified article.
     */
    public function show(int $id): JsonResponse
    {
        $article = $this->getArticleQuery->execute($id);

        if (! $article) {
            return response()->json([
                'message' => __('articles.messages.not_found'),
            ], 404);
        }

        return response()->json([
            'data' => $article,
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        // Статья уже получена и проверена в middleware
        $article = $request->get('article');

        $dto = UpdateArticleDTO::fromArray($request->validated());
        $updatedArticle = $this->updateArticleAction->execute($article, $dto);

        return response()->json([
            'data' => $updatedArticle->load(['author']),
            'message' => __('articles.messages.updated'),
        ]);
    }

    /**
     * Remove the specified article.
     */
    public function destroy(int $id): JsonResponse
    {
        // Статья уже получена и проверена в middleware
        $article = request()->get('article');

        $this->deleteArticleAction->execute($article);

        return response()->json([
            'message' => __('articles.messages.deleted'),
        ]);
    }
}
