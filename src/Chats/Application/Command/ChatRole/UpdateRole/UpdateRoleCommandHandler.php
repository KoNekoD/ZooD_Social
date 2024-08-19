<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatRole\UpdateRole;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\ValidationException;

class UpdateRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        private readonly ChatRoleRepositoryInterface $chatRoleRepository,
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     * @throws ValidationException
     * @throws NotYourProfileException
     */
    public function __invoke(UpdateRoleCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService
            ->authenticate($command->profileId, $command->chatId);

        // Только создатель может создавать роли
        if (!$participant->getRole()->isCreator())
        {
            throw new YouDoesNotOwnerException();
        }

        $role = $this->chatRoleRepository->findOne($participant->getChat(), $command->roleId);
        $role->updateRoleInformation($command->roleName, $command->roleStyle, $command->roleCanRestrict);

        $this->chatRoleRepository->save();
    }
}
