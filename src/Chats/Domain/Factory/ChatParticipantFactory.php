<?php

declare(strict_types=1);

namespace App\Chats\Domain\Factory;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Entity\ChatRole;
use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Specification\ChatParticipant\ChatParticipantSpecification;
use App\Profiles\Domain\Entity\Profile;

class ChatParticipantFactory
{
    public function __construct(
        private readonly ChatParticipantSpecification $chatParticipantSpecification,
    ) {
    }

    /**
     * @throws ParticipantAlreadyExistException
     */
    public function create(
        Profile $profile,
        Chat $chat,
        int $status,
        ChatRole $chatRole
    ): ChatParticipant {
        return new ChatParticipant($profile, $chat, $status, $chatRole, $this->chatParticipantSpecification);
    }
}
