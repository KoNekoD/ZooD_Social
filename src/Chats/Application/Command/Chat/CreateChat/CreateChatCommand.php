<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\CreateChat;

use App\Shared\Application\Command\CommandInterface;

class CreateChatCommand implements CommandInterface
{
    public function __construct(
        public readonly string $chatTitle,
        public readonly string $chatDescription,
        public readonly string $creatorProfileId,
    ) {
    }
}
