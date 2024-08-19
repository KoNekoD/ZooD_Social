<?php

declare(strict_types=1);

namespace App\Chats\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class MessageNotFoundException extends BaseException
{
    public function __construct(string $message = 'Message not found')
    {
        parent::__construct($message, self::CHATS_MESSAGE_NOT_FOUND);
    }

    public function getStatusCode(): int
    {
        return 404;
    }
}
