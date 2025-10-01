<?php

namespace App\Modules\Articles\Application\Actions;

use App\Modules\Articles\Application\Queries\GetArticleQuery;
use Illuminate\Http\Exceptions\HttpResponseException;

readonly class DeleteArticleWithAuthorizationAction
{
    public function __construct(
        private GetArticleQuery $getArticleQuery,
        private DeleteArticleAction $deleteArticleAction
    ) {
    }

    /**
     * Удаляет статью с проверкой авторизации и прав доступа
     *
     * @throws HttpResponseException
     */
    public function execute(int $articleId, int $userId): bool
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

        return $this->deleteArticleAction->execute($article);
    }
}

