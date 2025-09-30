<?php

namespace Tests\Modules\Comments\Unit;

use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Infrastructure\Repositories\CommentRepository;
use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCommentActionTest extends TestCase
{
    use RefreshDatabase;

    private DeleteCommentAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteCommentAction(new CommentRepository);
    }

    public function test_can_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $result = $this->action->execute($comment);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
