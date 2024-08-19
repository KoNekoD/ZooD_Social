<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\Kick;

use App\Shared\Application\Command\CommandInterface;

class KickCommand implements CommandInterface
{
    public function __construct(
        public readonly string $chatId,
        public readonly string $senderProfileId,
        public readonly string $destinationProfileId,
    ) {
    }
}
