<?php

namespace Tests\Modules\Comments\Unit;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Comments\Infrastructure\Repositories\CommentRepository;
use App\Modules\Comments\Persistence\ORM\Comment;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CommentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CommentRepository;
    }

    public function test_can_create_comment(): void
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $data = [
            'article_id' => $article->id,
            'author_id' => $user->id,
            'text' => 'This is a test comment',
        ];

        $comment = $this->repository->create($data);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals($data['article_id'], $comment->article_id);
        $this->assertEquals($data['author_id'], $comment->author_id);
        $this->assertEquals($data['text'], $comment->text);
        $this->assertDatabaseHas('comments', [
            'article_id' => $data['article_id'],
            'author_id' => $data['author_id'],
            'text' => $data['text'],
        ]);
    }

    public function test_can_find_comment_by_id(): void
    {
        $comment = Comment::factory()->create();

        $foundComment = $this->repository->findById($comment->id);

        $this->assertInstanceOf(Comment::class, $foundComment);
        $this->assertEquals($comment->id, $foundComment->id);
        $this->assertTrue($foundComment->relationLoaded('author'));
        $this->assertTrue($foundComment->relationLoaded('article'));
    }

    public function test_can_get_comments_by_article_id(): void
    {
        $article = Article::factory()->create();
        Comment::factory()->count(3)->create(['article_id' => $article->id]);
        Comment::factory()->count(2)->create(); // Other article comments

        $comments = $this->repository->getByArticleId($article->id);

        $this->assertCount(3, $comments);
        $this->assertTrue($comments->every(fn ($comment) => $comment->article_id === $article->id));
    }

    public function test_can_update_comment(): void
    {
        $comment = Comment::factory()->create();
        $updateData = ['text' => 'Updated comment text'];

        $updatedComment = $this->repository->update($comment, $updateData);

        $this->assertEquals($updateData['text'], $updatedComment->text);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => $updateData['text'],
        ]);
    }

    public function test_can_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $result = $this->repository->delete($comment);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
