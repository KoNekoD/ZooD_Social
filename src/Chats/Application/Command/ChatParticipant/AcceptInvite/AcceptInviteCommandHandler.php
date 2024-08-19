<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\AcceptInvite;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Shared\Application\Command\CommandHandlerInterface;

class AcceptInviteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(AcceptInviteCommand $command): void
    {
        $participant = $this->chatParticipantAuthenticatorService->authenticate($command->profileId, $command->chatId);

        // При условии что пользователь ИМЕННО приглашен
        if (ChatParticipant::STATUS_INVITED === $participant->getStatus())
        {
            $participant->setStatus(ChatParticipant::STATUS_MEMBER);

            $this->chatParticipantRepository->save();
        }
    }
}
