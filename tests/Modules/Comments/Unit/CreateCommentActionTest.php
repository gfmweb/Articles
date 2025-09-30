<?php

namespace Tests\Modules\Comments\Unit;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\DTOs\CreateCommentDTO;
use App\Modules\Comments\Infrastructure\Repositories\CommentRepository;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCommentActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateCommentAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateCommentAction(new CommentRepository);
    }

    public function test_can_create_comment(): void
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $dto = new CreateCommentDTO(
            article_id: $article->id,
            author_id: $user->id,
            text: 'This is a test comment'
        );

        $comment = $this->action->execute($dto);

        $this->assertEquals($dto->article_id, $comment->article_id);
        $this->assertEquals($dto->author_id, $comment->author_id);
        $this->assertEquals($dto->text, $comment->text);
        $this->assertDatabaseHas('comments', [
            'article_id' => $dto->article_id,
            'author_id' => $dto->author_id,
            'text' => $dto->text,
        ]);
    }
}
