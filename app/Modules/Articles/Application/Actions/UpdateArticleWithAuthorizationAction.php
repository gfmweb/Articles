<?php

namespace App\Modules\Articles\Application\Actions;

use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Application\Queries\GetArticleQuery;
use App\Modules\Articles\Application\Services\ArticleEnricherService;
use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Http\Exceptions\HttpResponseException;

readonly class UpdateArticleWithAuthorizationAction
{
    public function __construct(
        private GetArticleQuery $getArticleQuery,
        private UpdateArticleAction $updateArticleAction,
        private ArticleEnricherService $enricherService
    ) {
    }

    /**
     * Обновляет статью с проверкой авторизации и прав доступа
     *
     * @throws HttpResponseException
     */
    public function execute(int $articleId, UpdateArticleDTO $dto, int $userId): Article
    {
        $article = $this->getArticleQuery->execute($articleId);

        if (! $article) {
            throw new HttpResponseException(
                response()->json(['message' => __('articles::messages.not_found')], 404)
            );
        }

        if ($article->author_id !== $userId) {
            throw new HttpResponseException(
                response()->json(['message' => __('articles::messages.unauthorized')], 403)
            );
        }

        $updatedArticle = $this->updateArticleAction->execute($article, $dto);
        $updatedArticle->load(['author']);
        $this->enricherService->enrichArticle($updatedArticle, $userId);

        return $updatedArticle;
    }
}

