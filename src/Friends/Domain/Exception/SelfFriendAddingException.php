<?php

declare(strict_types=1);

namespace App\Friends\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class SelfFriendAddingException extends BaseException
{
    public function __construct(string $message = 'Adding herself in friends is not allowed')
    {
        parent::__construct($message, self::FRIENDS_SELF_FRIEND_ADDING);
    }

    public function getStatusCode(): int
    {
        return 403; // HTTP_FORBIDDEN
    }
}
