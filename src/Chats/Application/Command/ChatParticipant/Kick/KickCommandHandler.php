<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatParticipant\Kick;

use App\Chats\Domain\Exception\YouDoesNotCanRestrictException;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Service\ChatParticipantAuthenticatorService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

class KickCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly ChatParticipantAuthenticatorService $chatParticipantAuthenticatorService,
    ) {
    }

    /**
     * @throws YouDoesNotCanRestrictException
     * @throws NotYourProfileException
     */
    public function __invoke(KickCommand $command): void
    {
        $kicker = $this->chatParticipantAuthenticatorService
            ->authenticate($command->senderProfileId, $command->chatId);

        if (!$kicker->getRole()->isCanRestrict())
        {
            throw new YouDoesNotCanRestrictException();
        }

        $destinationProfile = $this->profileRepository->findById($command->destinationProfileId);
        $destinationParticipant = $this->chatParticipantRepository->findOne($destinationProfile, $kicker->getChat());

        // Просто удаляем его из чата
        $this->chatParticipantRepository->remove($destinationParticipant);
    }
}
