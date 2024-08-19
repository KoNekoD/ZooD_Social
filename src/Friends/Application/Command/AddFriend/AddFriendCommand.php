<?php

declare(strict_types=1);

namespace App\Friends\Application\Command\AddFriend;

use App\Shared\Application\Command\CommandInterface;

class AddFriendCommand implements CommandInterface
{
    public function __construct(public readonly string $senderProfileId, public readonly string $destinationProfileId)
    {
    }
}
