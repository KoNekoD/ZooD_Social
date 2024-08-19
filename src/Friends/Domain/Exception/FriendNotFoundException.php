<?php

declare(strict_types=1);

namespace App\Friends\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class FriendNotFoundException extends BaseException
{
    public function __construct(string $message = 'Friend not found')
    {
        parent::__construct($message, self::FRIENDS_FRIEND_NOT_FOUND);
    }

    public function getStatusCode(): int
    {
        return 404;
    }
}
