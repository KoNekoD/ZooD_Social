<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\RejectInvite;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;

class RejectInviteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(RejectInviteCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService
            ->authenticate($command->profileId, $command->chatId);

        // При условии что пользователь ИМЕННО приглашен
        if (ChatParticipant::STATUS_INVITED === $participant->getStatus())
        {
            // Просто удаляем его из чата
            $this->chatParticipantRepository->remove($participant);
        }
    }
}
