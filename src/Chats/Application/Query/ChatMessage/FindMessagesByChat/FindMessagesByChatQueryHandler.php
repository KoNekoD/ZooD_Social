<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\ChatMessage\FindMessagesByChat;

use App\Chats\Application\DTO\MessageByChatDTO;
use App\Chats\Domain\Repository\ChatMessageRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Query\QueryHandlerInterface;

class FindMessagesByChatQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        private readonly ChatMessageRepositoryInterface $chatMessageRepository,
    ) {
    }

    /**
     * @return array<MessageByChatDTO>
     *
     * @throws NotYourProfileException
     */
    public function __invoke(FindMessagesByChatQuery $command): array
    {
        // Только участники чата могут получать историю чата
        $participant = $this->chatParticipantAuthenticatorService->authenticate($command->profileId, $command->chatId);

        $messages = $this->chatMessageRepository->findByChat($participant->getChat(), $command->page);

        $result = [];

        foreach ($messages as $message)
        {
            $result[] = MessageByChatDTO::fromEntity($message);
        }

        return $result;
    }
}
