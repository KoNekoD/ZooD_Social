<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\CreateProfile;

use App\Profiles\Domain\Factory\ProfileFactory;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\Users\Domain\Repository\UserRepositoryInterface;

class CreateProfileCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProfileFactory $profileFactory,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function __invoke(CreateProfileCommand $command): void
    {
        $auth_user = $this->userFetcher->getAuthUser();
        $user = $this->userRepository->findById($auth_user->getId());

        $profile = $this->profileFactory->create($user, $command->firstName, $command->lastName);

        $this->profileRepository->create($profile);
    }
}
