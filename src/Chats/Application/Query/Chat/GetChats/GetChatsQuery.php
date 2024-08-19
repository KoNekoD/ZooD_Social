<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\Chat\GetChats;

use App\Shared\Application\Query\QueryInterface;

class GetChatsQuery implements QueryInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly int $page = 1,
    ) {
    }
}
