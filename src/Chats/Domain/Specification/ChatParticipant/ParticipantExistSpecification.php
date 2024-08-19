<?php

declare(strict_types=1);

namespace App\Chats\Domain\Specification\ChatParticipant;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;

class ParticipantExistSpecification
{
    public function __construct(
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * @throws ParticipantAlreadyExistException
     */
    public function satisfy(ChatParticipant $chatParticipant): void
    {
        if ($this->chatParticipantRepository
            ->isExist(
                $chatParticipant->getProfile(),
                $chatParticipant->getChat()
            ))
        {
            throw new ParticipantAlreadyExistException();
        }
    }
}
