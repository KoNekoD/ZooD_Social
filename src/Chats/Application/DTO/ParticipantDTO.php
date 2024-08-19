<?php

declare(strict_types=1);

namespace App\Chats\Application\DTO;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Profiles\Application\DTO\ProfileDTO;

class ParticipantDTO
{
    public ProfileDTO $profile;
    public int $roleId;
    public int $status;
    // Тут может нехватать поля чата

    public static function fromEntity(ChatParticipant $chatParticipant): self
    {
        $self = new self();

        $self->profile = ProfileDTO::fromEntity($chatParticipant->getProfile());
        $self->roleId = $chatParticipant->getRole()->getId();
        $self->status = $chatParticipant->getStatus();

        return $self;
    }
}
