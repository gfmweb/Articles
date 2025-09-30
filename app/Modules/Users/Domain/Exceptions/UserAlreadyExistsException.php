<?php

namespace App\Modules\Users\Domain\Exceptions;

use DomainException;

class UserAlreadyExistsException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct(__('users::messages.email_exists')." Email: {$email}");
    }
}
