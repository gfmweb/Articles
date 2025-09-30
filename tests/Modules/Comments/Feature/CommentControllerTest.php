<?php

namespace Tests\Modules\Comments\Feature;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Comments\Persistence\ORM\Comment;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_comment(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);
        $token = $user->createToken('test-token')->plainTextToken;

        $commentData = [
            'text' => 'Great article!',
            'author_id' => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson("/api/articles/{$article->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'article_id', 'author_id', 'text', 'author'],
                'message',
            ]);

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'author_id' => $user->id,
            'text' => 'Great article!',
        ]);
    }

    public function test_can_delete_comment(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $user->id,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('comments::messages.deleted'),
            ]);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_cannot_create_comment_without_auth(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $commentData = [
            'text' => 'Great article!',
            'author_id' => $user->id,
        ];

        $response = $this->postJson("/api/articles/{$article->id}/comments", $commentData);

        $response->assertStatus(401);
    }

    public function test_cannot_delete_comment_of_another_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user1->id]);
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $user1->id,
        ]);
        $token = $user2->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403);
    }
}
