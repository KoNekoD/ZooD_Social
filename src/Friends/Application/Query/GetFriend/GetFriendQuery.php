<?php

declare(strict_types=1);

namespace App\Friends\Application\Query\GetFriend;

use App\Shared\Application\Query\QueryInterface;

class GetFriendQuery implements QueryInterface
{
    public function __construct(public readonly string $senderProfileId, public readonly string $destinationProfileId)
    {
    }
}
