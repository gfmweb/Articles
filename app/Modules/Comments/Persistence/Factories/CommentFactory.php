<?php

namespace App\Modules\Comments\Persistence\Factories;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Comments\Persistence\ORM\Comment;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * @var class-string<Comment>
     */
    protected $model = Comment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'author_id' => User::factory(),
            'text' => fake()->paragraphs(2, true),
        ];
    }
}
