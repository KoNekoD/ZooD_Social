<?php

declare(strict_types=1);

namespace App\Friends\Application\Command\RemoveFriend;

use App\Friends\Domain\Service\FriendService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Application\Command\CommandHandlerInterface;

class RemoveFriendCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly FriendService $friendService,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(RemoveFriendCommand $command): void
    {
        $senderProfile = $this->profileAuthenticatorService->authenticate($command->senderProfileId);
        $destinationProfile = $this->profileRepository->findById($command->destinationProfileId);

        $this->friendService->removeFromFriends($senderProfile, $destinationProfile);
    }
}
