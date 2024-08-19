<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\AcceptInvite;

use App\Shared\Application\Command\CommandInterface;

class AcceptInviteCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId
    ) {
    }
}
