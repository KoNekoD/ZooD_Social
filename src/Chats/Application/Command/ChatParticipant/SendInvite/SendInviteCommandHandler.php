<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\SendInvite;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Factory\ChatParticipantFactory;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

class SendInviteCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly ChatParticipantFactory $participantFactory,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
        private readonly ChatRoleRepositoryInterface $chatRoleRepository,
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
    ) {
    }

    /**
     * @throws ParticipantAlreadyExistException
     * @throws YouDoesNotOwnerException
     * @throws NotYourProfileException
     */
    public function __invoke(SendInviteCommand $command): void
    {
        // Удостоверимся что тот кто приглашает есть в чате
        $inviter = $this->chatParticipantAuthenticatorService
            ->authenticate($command->senderProfileId, $command->chatId);

        // Place to feature На данный момент в чат инвайтить могут только владельцы чатов
        if (!$inviter->getRole()->isCreator())
        {
            // И незабудь изменить exception на свой
            throw new YouDoesNotOwnerException();
        }

        // TODO.php add this checking in friends

        $destinationProfile = $this->profileRepository->findById($command->destinationProfileId);

        $destinationRole = $this->chatRoleRepository->findOne($inviter->getChat(), $command->roleId);

        $destinationParticipant = $this->participantFactory
            ->create(
                $destinationProfile,
                $inviter->getChat(),
                ChatParticipant::STATUS_INVITED,
                $destinationRole,
            );

        $this->chatParticipantRepository->add($destinationParticipant);
    }
}
