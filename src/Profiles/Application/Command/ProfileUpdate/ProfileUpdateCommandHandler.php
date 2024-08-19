<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\ProfileUpdate;

use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Application\Command\CommandHandlerInterface;

class ProfileUpdateCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(ProfileUpdateCommand $command): void
    {
        $profile = $this->profileAuthenticatorService->authenticate($command->profileId);

        $profile->updateProfileInformation($command->firstName, $command->lastName);

        $this->profileRepository->save();
    }
}
