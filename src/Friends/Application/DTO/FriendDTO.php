<?php

declare(strict_types=1);

namespace App\Friends\Application\DTO;

use App\Friends\Domain\Entity\Friend;

class FriendDTO
{
    public function __construct(public readonly string $senderProfileId, public readonly string $destinationProfileId, public readonly int $relationType)
    {
    }

    public static function fromEntity(Friend $friend): self
    {
        return new self($friend->getProfile()->getId(), $friend->getFriend()->getId(), $friend->getRelationType());
    }
}
