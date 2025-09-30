<?php

namespace App\Modules\Comments\Domain\Exceptions;

use DomainException;

class CommentNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(__('comments::messages.not_found'));
    }
}
