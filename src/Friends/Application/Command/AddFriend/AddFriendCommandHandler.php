<?php

declare(strict_types=1);

namespace App\Friends\Application\Command\AddFriend;

use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;
use App\Friends\Domain\Service\FriendService;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Application\Command\CommandHandlerInterface;

class AddFriendCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly FriendService $friendService
    ) {
    }

    /**
     * @throws UsingSharedUserException
     * @throws NotYourProfileException
     * @throws SelfFriendAddingException
     */
    public function __invoke(AddFriendCommand $command): void
    {
        $senderProfile = $this->profileAuthenticatorService->authenticate($command->senderProfileId);
        $destinationProfile = $this->profileRepository->findById($command->destinationProfileId);

        $this->friendService->addInFriends($senderProfile, $destinationProfile);
    }
}
