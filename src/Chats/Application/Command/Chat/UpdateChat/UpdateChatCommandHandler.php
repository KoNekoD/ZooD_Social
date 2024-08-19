<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\UpdateChat;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\ValidationException;

class UpdateChatCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        private readonly ChatRepositoryInterface $chatRepository,
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     * @throws ValidationException
     * @throws NotYourProfileException
     */
    public function __invoke(UpdateChatCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService->authenticate($command->profileId, $command->chatId);

        // Place to add /feature/canUpdateChatInformationRoleParameter
        // Simply modify if statement isCreator() to isCanUpdateChatInformation()
        if (!$participant->getRole()->isCreator())
        {
            // And change this exception to yours:
            throw new YouDoesNotOwnerException();
        }

        $participant
            ->getChat()->
            updateChatInformation(
                $command->chatTitle,
                $command->chatDescription,
                // Place to add /feature/chatAvatar
            );

        $this->chatRepository->save();
    }
}
