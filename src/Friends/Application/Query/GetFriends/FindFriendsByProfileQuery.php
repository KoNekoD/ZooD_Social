<?php

declare(strict_types=1);

namespace App\Friends\Application\Query\GetFriends;

use App\Shared\Application\Query\QueryInterface;

class FindFriendsByProfileQuery implements QueryInterface
{
    public function __construct(
        public readonly string $senderProfileId,
        public readonly int $relationType,
        public readonly int $page,
    ) {
    }
}
