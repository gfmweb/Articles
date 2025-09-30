<?php

namespace App\Modules\Articles\Infrastructure\Repositories;

use App\Modules\Articles\Persistence\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Persistence\ORM\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function create(array $data): Article
    {
        return DB::transaction(function () use ($data) {
            return Article::create($data);
        });
    }

    public function findById(int $id): ?Article
    {
        return Article::find($id);
    }

    public function findByIdWithComments(int $id): ?Article
    {
        return Article::with(['comments.author', 'author'])
            ->withCount('comments')
            ->find($id);
    }

    public function getAllPaginated(int $perPage = 15, ?int $userId = null): LengthAwarePaginator
    {
        $query = Article::query()
            ->with(['author'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->addSelect(['articles.*'])
                ->selectRaw('(author_id = ?) as is_author', [$userId])
                ->selectRaw(
                    'EXISTS(SELECT 1 FROM comments WHERE comments.article_id = articles.id AND comments.author_id = ?) as has_commented',
                    [$userId]
                );
        } else {
            $query->select('articles.*');
        }

        $paginator = $query->paginate($perPage);

        if ($userId) {
            $paginator->getCollection()->each(function ($article) {
                $article->setAttribute('is_author', (bool) $article->getRawOriginal('is_author'));
                $article->setAttribute('has_commented', (bool) $article->getRawOriginal('has_commented'));
            });
        }

        return $paginator;
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
        return DB::transaction(function () use ($article, $data) {
            $article->update($data);

            return $article->fresh();
        });
    }

    public function delete(Article $article): bool
    {
        return DB::transaction(function () use ($article) {
            return $article->delete();
        });
    }
}
