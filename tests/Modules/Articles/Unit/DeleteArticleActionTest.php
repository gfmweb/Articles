<?php

namespace Tests\Modules\Articles\Unit;

use App\Modules\Articles\Application\Actions\DeleteArticleAction;
use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteArticleActionTest extends TestCase
{
    use RefreshDatabase;

    private DeleteArticleAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteArticleAction(new ArticleRepository);
    }

    public function test_can_delete_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $result = $this->action->execute($article);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
