<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatRole\AddRole;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Factory\ChatRoleFactory;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\ValidationException;

class AddRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatRoleFactory $roleFactory,
        private readonly ChatRoleRepositoryInterface $chatRoleRepository,
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     * @throws ValidationException
     * @throws NotYourProfileException
     */
    public function __invoke(AddRoleCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService
            ->authenticate($command->profileId, $command->chatId);

        // Только создатель может создавать роли
        if (!$participant->getRole()->isCreator())
        {
            throw new YouDoesNotOwnerException();
        }

        $role = $this->roleFactory->create(
            $participant->getChat(),
            $command->roleName,
            $command->roleStyle,
            false,
            $command->roleCanRestrict,
            false
        );

        $this->chatRoleRepository->create($role);
    }
}
