<?php

namespace Tests\Feature;

use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_brute_force_protection(): void
    {
        // Попытка множественных неудачных входов
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // После 5 попыток должен быть заблокирован
        $response->assertStatus(429)
            ->assertJsonStructure(['message', 'retry_after'])
            ->assertJson([
                'message' => __('auth.throttle', ['seconds' => $response->json('retry_after')]),
            ]);
    }

    public function test_rate_limiting_resets_after_time(): void
    {
        // Исчерпываем лимит
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // Проверяем, что заблокированы
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(429);

        // Очищаем rate limiter (в реальном тесте нужно ждать)
        \Illuminate\Support\Facades\RateLimiter::clear('auth.'.$this->app['request']->ip());

        // Теперь должен работать
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(401); // Не 429, значит rate limiting сброшен
    }

    public function test_xss_protection_in_articles(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $maliciousContent = '<script>alert("XSS")</script>';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/articles', [
            'title' => 'Test Article',
            'content' => $maliciousContent,
            'author_id' => $user->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    public function test_sql_injection_protection(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $sqlInjection = "'; DROP TABLE users; --";

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/articles', [
            'title' => $sqlInjection,
            'content' => 'Test content',
            'author_id' => $user->id,
        ]);

        // Должен обработать как обычный текст, не выполнить SQL
        $response->assertStatus(201);

        // Проверяем, что таблица users все еще существует
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_unauthorized_access_denied(): void
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }

    public function test_csrf_protection_for_api(): void
    {
        // API не должен требовать CSRF токен
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    public function test_registration_rate_limiting(): void
    {
        // Попытка множественной регистрации
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/register', [
                'name' => 'Test User '.$i,
                'email' => 'test'.$i.'@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);
        }

        // После 5 попыток должен быть заблокирован
        $response->assertStatus(429);
    }

    public function test_article_creation_rate_limiting(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Создаем 4 статьи (лимит 3 в минуту)
        for ($i = 0; $i < 4; $i++) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/articles', [
                'title' => 'Test Article '.$i,
                'content' => 'Test content '.$i,
                'author_id' => $user->id,
            ]);
        }

        // После 3 статей должен быть заблокирован
        $response->assertStatus(429)
            ->assertJsonStructure(['message', 'retry_after'])
            ->assertJson([
                'message' => __('articles.messages.rate_limit_exceeded', ['seconds' => $response->json('retry_after')]),
            ]);
    }

    public function test_comment_creation_rate_limiting(): void
    {
        $user = User::factory()->create();
        $article = \App\Modules\Articles\Persistence\ORM\Article::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Создаем первый комментарий
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson("/api/articles/{$article->id}/comments", [
            'text' => 'First comment',
            'author_id' => $user->id,
        ]);

        $response->assertStatus(201);

        // Сразу пытаемся создать второй комментарий (лимит 1 в 5 секунд)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson("/api/articles/{$article->id}/comments", [
            'text' => 'Second comment',
            'author_id' => $user->id,
        ]);

        // Должен быть заблокирован
        $response->assertStatus(429)
            ->assertJsonStructure(['message', 'retry_after'])
            ->assertJson([
                'message' => __('comments.messages.rate_limit_exceeded', ['seconds' => $response->json('retry_after')]),
            ]);
    }
}
