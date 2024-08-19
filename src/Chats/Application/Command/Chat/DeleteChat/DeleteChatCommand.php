<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\DeleteChat;

use App\Shared\Application\Command\CommandInterface;

class DeleteChatCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId
    ) {
    }
}
