<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\Chat\FindChat;

use App\Shared\Application\Query\QueryInterface;

class FindChatByIdQuery implements QueryInterface
{
    public function __construct(public readonly string $chatId)
    {
    }
}
