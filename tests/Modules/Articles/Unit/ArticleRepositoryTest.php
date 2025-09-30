<?php

namespace Tests\Modules\Articles\Unit;

use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ArticleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ArticleRepository;
    }

    public function test_can_create_article(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Test Article',
            'content' => 'This is test content',
            'author_id' => $user->id,
        ];

        $article = $this->repository->create($data);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals($data['title'], $article->title);
        $this->assertEquals($data['content'], $article->content);
        $this->assertEquals($data['author_id'], $article->author_id);
        $this->assertDatabaseHas('articles', [
            'title' => $data['title'],
            'content' => $data['content'],
            'author_id' => $data['author_id'],
        ]);
    }

    public function test_can_find_article_by_id(): void
    {
        $article = Article::factory()->create();

        $foundArticle = $this->repository->findById($article->id);

        $this->assertInstanceOf(Article::class, $foundArticle);
        $this->assertEquals($article->id, $foundArticle->id);
    }

    public function test_can_find_article_by_id_with_comments(): void
    {
        $article = Article::factory()->create();

        $foundArticle = $this->repository->findByIdWithComments($article->id);

        $this->assertInstanceOf(Article::class, $foundArticle);
        $this->assertEquals($article->id, $foundArticle->id);
        $this->assertTrue($foundArticle->relationLoaded('comments'));
        $this->assertTrue($foundArticle->relationLoaded('author'));
    }

    public function test_can_get_all_articles_paginated(): void
    {
        Article::factory()->count(5)->create();

        $articles = $this->repository->getAllPaginated(2);

        $this->assertCount(2, $articles->items());
        $this->assertEquals(5, $articles->total());
    }

    public function test_can_update_article(): void
    {
        $article = Article::factory()->create();
        $updateData = ['title' => 'Updated Title'];

        $updatedArticle = $this->repository->update($article, $updateData);

        $this->assertEquals($updateData['title'], $updatedArticle->title);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $updateData['title'],
        ]);
    }

    public function test_can_delete_article(): void
    {
        $article = Article::factory()->create();

        $result = $this->repository->delete($article);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
