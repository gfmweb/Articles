<?php

namespace Tests\Modules\Articles\Unit;

use App\Modules\Articles\Application\Actions\CreateArticleAction;
use App\Modules\Articles\Application\DTOs\CreateArticleDTO;
use App\Modules\Articles\Infrastructure\Repositories\ArticleRepository;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateArticleActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateArticleAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateArticleAction(new ArticleRepository);
    }

    public function test_can_create_article(): void
    {
        $user = User::factory()->create();

        $dto = new CreateArticleDTO(
            title: 'Test Article',
            content: 'This is test content',
            author_id: $user->id
        );

        $article = $this->action->execute($dto);

        $this->assertEquals($dto->title, $article->title);
        $this->assertEquals($dto->content, $article->content);
        $this->assertEquals($dto->author_id, $article->author_id);
        $this->assertDatabaseHas('articles', [
            'title' => $dto->title,
            'content' => $dto->content,
            'author_id' => $dto->author_id,
        ]);
    }
}
