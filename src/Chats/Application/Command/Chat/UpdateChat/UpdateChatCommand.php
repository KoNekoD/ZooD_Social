<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\UpdateChat;

use App\Shared\Application\Command\CommandInterface;

class UpdateChatCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId,
        public readonly string $chatTitle,
        public readonly string $chatDescription,
        // Place to add /feature/chatAvatar
    ) {
    }
}
