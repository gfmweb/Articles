<?php

namespace App\Modules\Articles\Persistence\Factories;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * @var class-string<Article>
     */
    protected $model = Article::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'content' => fake()->paragraphs(3, true),
            'author_id' => User::factory(),
        ];
    }
}
