<?php

namespace Tests\Modules\Articles\Unit;

use App\Modules\Articles\Application\Actions\UpdateArticleAction;
use App\Modules\Articles\Application\DTOs\UpdateArticleDTO;
use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateArticleActionTest extends TestCase
{
    use RefreshDatabase;

    private UpdateArticleAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateArticleAction(new ArticleRepository);
    }

    public function test_can_update_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $dto = new UpdateArticleDTO(
            title: 'Updated Title',
            content: 'Updated content'
        );

        $updatedArticle = $this->action->execute($article, $dto);

        $this->assertEquals('Updated Title', $updatedArticle->title);
        $this->assertEquals('Updated content', $updatedArticle->content);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    }

    public function test_can_update_article_partially(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $user->id,
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);

        $dto = new UpdateArticleDTO(
            title: 'Updated Title'
        );

        $updatedArticle = $this->action->execute($article, $dto);

        $this->assertEquals('Updated Title', $updatedArticle->title);
        $this->assertEquals('Original content', $updatedArticle->content);
    }
}
