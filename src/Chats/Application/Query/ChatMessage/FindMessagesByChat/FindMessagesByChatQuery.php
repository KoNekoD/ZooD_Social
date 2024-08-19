<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\ChatMessage\FindMessagesByChat;

use App\Shared\Application\Query\QueryInterface;

class FindMessagesByChatQuery implements QueryInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId,
        public readonly int $page,
    ) {
    }
}
