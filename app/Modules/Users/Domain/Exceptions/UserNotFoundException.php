<?php

namespace App\Modules\Users\Domain\Exceptions;

use DomainException;

class UserNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(__('users::messages.not_found'));
    }
}
