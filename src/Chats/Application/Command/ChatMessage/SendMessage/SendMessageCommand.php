<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatMessage\SendMessage;

use App\Shared\Application\Command\CommandInterface;

class SendMessageCommand implements CommandInterface
{
    public function __construct(
        public readonly string $fromId,
        public readonly string $chatId,
        public readonly string $content,
    ) {
    }
}
