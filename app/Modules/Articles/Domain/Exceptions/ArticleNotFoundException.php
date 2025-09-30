<?php

namespace App\Modules\Articles\Domain\Exceptions;

use DomainException;

class ArticleNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(__('articles::messages.not_found'));
    }
}
