<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\RemoveProfile;

use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Application\Command\CommandHandlerInterface;

class RemoveProfileCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function __invoke(RemoveProfileCommand $command): void
    {
        $profile = $this->profileAuthenticatorService->authenticate($command->profileId);

        $this->profileRepository->remove($profile);
    }
}
