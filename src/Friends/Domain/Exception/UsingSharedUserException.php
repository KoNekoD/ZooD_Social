<?php

declare(strict_types=1);

namespace App\Friends\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class UsingSharedUserException extends BaseException
{
    public function __construct(string $message = 'Using shared user with adding in friends is not allowed')
    {
        parent::__construct($message, self::FRIENDS_USING_SHARED_USER);
    }

    public function getStatusCode(): int
    {
        return 403; // HTTP_FORBIDDEN
    }
}
