<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\ChatParticipant\GetParticipants;

use App\Chats\Application\DTO\ParticipantDTO;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetParticipantsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * @return array<ParticipantDTO>
     */
    public function __invoke(GetParticipantsQuery $query): array
    {
        $chat = $this->chatRepository->findById($query->chatId);

        $chatParticipantArray = $this->chatParticipantRepository->findByChat($chat, $query->page);

        $result = [];

        foreach ($chatParticipantArray as $participant)
        {
            $result[] = ParticipantDTO::fromEntity($participant);
        }

        return $result;
    }
}
