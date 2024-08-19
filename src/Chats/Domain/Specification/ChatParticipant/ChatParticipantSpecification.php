<?php

declare(strict_types=1);

namespace App\Chats\Domain\Specification\ChatParticipant;

class ChatParticipantSpecification
{
    public function __construct(
        public readonly ParticipantExistSpecification $participantExistSpecification,
    ) {
    }
}
