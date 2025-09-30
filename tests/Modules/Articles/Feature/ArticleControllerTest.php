<?php

namespace Tests\Modules\Articles\Feature;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_articles(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(3)->create(['author_id' => $user->id]);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'author_id', 'author'],
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    public function test_can_get_single_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'content', 'author_id', 'author', 'comments'],
            ]);
    }

    public function test_can_create_article(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $articleData = [
            'title' => 'Test Article',
            'content' => 'This is test content',
            'author_id' => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/articles', $articleData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'content', 'author_id', 'author'],
                'message',
            ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'content' => 'This is test content',
            'author_id' => $user->id,
        ]);
    }

    public function test_can_update_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $updateData = [
            'title' => 'Updated Title',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/articles/{$article->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'content', 'author_id', 'author'],
                'message',
            ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_can_delete_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('articles::messages.deleted'),
            ]);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_cannot_update_article_without_auth(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user->id]);

        $updateData = ['title' => 'Updated Title'];

        $response = $this->putJson("/api/articles/{$article->id}", $updateData);

        $response->assertStatus(401);
    }

    public function test_cannot_update_article_of_another_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $article = Article::factory()->create(['author_id' => $user1->id]);

        $updateData = ['title' => 'Updated Title'];

        $response = $this->actingAs($user2, 'sanctum')
            ->putJson("/api/articles/{$article->id}", $updateData);

        $response->assertStatus(403);
    }
}
