<?php

namespace App\Modules\Articles\Presentation\Controllers;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\Actions\DeleteArticleWithAuthorizationAction;
use App\Modules\Articles\Application\Actions\UpdateArticleWithAuthorizationAction;
use App\Modules\Articles\Application\DTOs\CreateArticleDTO;
use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Queries\GetArticlesQuery;
use App\Modules\Articles\Application\Services\ArticleEnricherService;
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
        private UpdateArticleWithAuthorizationAction $updateArticleWithAuthorizationAction,
        private DeleteArticleWithAuthorizationAction $deleteArticleWithAuthorizationAction,
        private ArticleEnricherService $enricherService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $userId = $request->user()?->id;

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
        $this->enricherService->enrichArticle($article, $request->user()->id);

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

        if (! $userId) {
            return response()->json(['message' => __('articles::messages.unauthenticated')], 401);
        }

        $dto = UpdateArticleDTO::fromArray($request->validated());
        $updatedArticle = $this->updateArticleWithAuthorizationAction->execute($id, $dto, $userId);

        return (new ArticleResource($updatedArticle))
            ->additional(['message' => __('articles::messages.updated')])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()?->id;

        if (! $userId) {
            return response()->json(['message' => __('articles::messages.unauthenticated')], 401);
        }

        $this->deleteArticleWithAuthorizationAction->execute($id, $userId);

        return response()->json([
            'message' => __('articles::messages.deleted'),
        ]);
    }
}
