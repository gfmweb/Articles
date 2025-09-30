<?php

namespace App\Modules\Articles\Domain\Policies;

use App\Modules\Articles\Persistence\ORM\Article;
use App\Modules\Users\Persistence\ORM\User;

class ArticlePolicy
{
    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->author_id;
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->author_id;
    }
}
