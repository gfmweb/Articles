<?php

namespace App\Modules\Articles\Infrastructure\Repositories;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function findById(int $id): ?Article
    {
        return Article::find($id);
    }

    public function findByIdWithComments(int $id): ?Article
    {
        return Article::with(['comments.author', 'author'])->find($id);
    }

    public function getAllPaginated(int $perPage = 15, ?int $userId = null): LengthAwarePaginator
    {
        $query = Article::with(['author'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->addSelect(['*'])
                ->selectRaw('(author_id = ?) as is_author', [$userId])
                ->selectRaw(
                    '(SELECT COUNT(*) > 0 FROM comments WHERE comments.article_id = articles.id AND comments.author_id = ?) as has_commented',
                    [$userId]
                );
        }

        return $query->paginate($perPage);
    }

    /**
     * @return Collection<int, Article>
     */
    public function getAll(): Collection
    {
        return Article::with(['author'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function update(Article $article, array $data): Article
    {
        $article->update($data);

        return $article->fresh();
    }

    public function delete(Article $article): bool
    {
        return $article->delete();
    }
}
