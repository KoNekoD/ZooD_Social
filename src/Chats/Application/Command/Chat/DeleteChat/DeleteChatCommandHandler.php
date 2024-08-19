<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\DeleteChat;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Shared\Application\Command\CommandHandlerInterface;

class DeleteChatCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     */
    public function __invoke(DeleteChatCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService->authenticate($command->profileId, $command->chatId);

        if (!$participant->getRole()->isCreator())
        {
            throw new YouDoesNotOwnerException();
        }

        // Automatically cascade deletes all child entities
        $this->chatRepository->remove($participant->getChat());
    }
}
