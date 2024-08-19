<?php

declare(strict_types=1);

namespace App\Chats\Domain\Service;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Factory\ChatFactory;
use App\Chats\Domain\Factory\ChatParticipantFactory;
use App\Chats\Domain\Factory\ChatRoleFactory;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Domain\Exception\ValidationException;

class ChatCreationService
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,

        private readonly ChatFactory $chatFactory,
        private readonly ChatRepositoryInterface $chatRepository,

        private readonly ChatRoleFactory $chatRoleFactory,
        private readonly ChatRoleRepositoryInterface $chatRoleRepository,

        private readonly ChatParticipantFactory $chatParticipantFactory,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws ParticipantAlreadyExistException
     * @throws NotYourProfileException
     */
    public function create(string $chatTitle, string $chatDescription, string $creatorProfileId): void
    {
        // Creating chat
        $chat = $this->chatFactory->create($chatTitle, $chatDescription);
        $this->chatRepository->create($chat);

        // Creating basic chat roles
        $ownerRole = $this->chatRoleFactory->create($chat, 'Владелец', 'color:orange;', true, true, false);
        $memberRole = $this->chatRoleFactory->create($chat, 'Участник', '', false, false, true);
        $this->chatRoleRepository->create($ownerRole);
        $this->chatRoleRepository->create($memberRole);

        // Adding creatorProfile in chat with owner role
        $profileEntity = $this->profileAuthenticatorService->authenticate($creatorProfileId);
        $creator = $this->chatParticipantFactory->create($profileEntity, $chat, ChatParticipant::STATUS_MEMBER, $ownerRole);
        $this->chatParticipantRepository->add($creator);
    }
}
