<?php

namespace App\Modules\Comments\Domain\Policies;

use App\Modules\Comments\Persistence\ORM\Comment;
use App\Modules\Users\Persistence\ORM\User;

class CommentPolicy
{
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->author_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->author_id;
    }
}
