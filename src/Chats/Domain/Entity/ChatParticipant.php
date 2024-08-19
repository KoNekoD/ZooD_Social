<?php

declare(strict_types=1);

namespace App\Chats\Domain\Entity;

use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Specification\ChatParticipant\ChatParticipantSpecification;
use App\Profiles\Domain\Entity\Profile;

class ChatParticipant
{
    private Profile $profile;
    private Chat $chat;
    private int $status;
    private ?ChatRole $role;

    public const PER_PAGE_LIMIT = 200;

    /**
     * @throws ParticipantAlreadyExistException
     */
    public function __construct(
        Profile $profile,
        Chat $chat,
        int $status,
        ChatRole $role,
        ChatParticipantSpecification $chatParticipantSpecification
    ) {
        $this->profile = $profile;
        $this->chat = $chat;
        $this->status = $status;
        $this->role = $role;

        // Using only in constructor
        $chatParticipantSpecification->participantExistSpecification->satisfy($this);
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getRole(): ChatRole
    {
        if (null === $this->role)
        {
            foreach ($this->chat->getRoles() as $role)
            {
                if ($role->isDefault())
                {
                    $this->role = $role;
                    break;
                }
            }
        }

        return $this->role;
    }

    public const STATUS_INVITED = 0;
    public const STATUS_MEMBER = 1;
    // ...
}
