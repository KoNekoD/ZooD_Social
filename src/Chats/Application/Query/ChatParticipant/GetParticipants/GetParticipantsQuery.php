<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\ChatParticipant\GetParticipants;

use App\Shared\Application\Query\QueryInterface;

class GetParticipantsQuery implements QueryInterface
{
    public function __construct(
        public readonly string $chatId,
        public readonly int $page
    ) {
    }
}
