<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatMessage\SendMessage;

use App\Chats\Domain\Factory\ChatMessageFactory;
use App\Chats\Domain\Repository\ChatMessageRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;

class SendMessageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        private readonly ChatMessageFactory $messageFactory,
        private readonly ChatMessageRepositoryInterface $messageRepository,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(SendMessageCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService->authenticate($command->fromId, $command->chatId);

        $message = $this->messageFactory->create($participant->getProfile(), $participant->getChat(), $command->content);

        $this->messageRepository->add($message);
    }
}
