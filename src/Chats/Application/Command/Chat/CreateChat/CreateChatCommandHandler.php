<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\Chat\CreateChat;

use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Service\ChatCreationService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\ValidationException;

class CreateChatCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatCreationService $chatCreationService,
    ) {
    }

    /**
     * @throws ParticipantAlreadyExistException
     * @throws NotYourProfileException
     * @throws ValidationException
     */
    public function __invoke(CreateChatCommand $command): void
    {
        $this->chatCreationService->create($command->chatTitle, $command->chatDescription, $command->creatorProfileId);
    }
}
