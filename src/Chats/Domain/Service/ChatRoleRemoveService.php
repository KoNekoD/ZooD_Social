<?php

declare(strict_types=1);

namespace App\Chats\Domain\Service;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use App\Profiles\Domain\Exception\NotYourProfileException;

class ChatRoleRemoveService
{
    public function __construct(
        public readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        public readonly ChatRoleRepositoryInterface $chatRoleRepository,
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     * @throws NotYourProfileException
     */
    public function remove(
        string $profileId,
        string $chatId,
        int $roleId
    ): void {
        $participant = $this->chatParticipantAuthenticatorService
            ->authenticate($profileId, $chatId);

        // Только создатель может удалять роли
        if (!$participant->getRole()->isCreator())
        {
            throw new YouDoesNotOwnerException();
        }

        $destinationRole = $this->chatRoleRepository
            ->findOne($participant->getChat(), $roleId);

        $this->chatRoleRepository->remove($destinationRole);
    }
}
