<?php

declare(strict_types=1);

namespace App\Profiles\Application\Query\GetProfiles;

use App\Profiles\Application\DTO\ProfileDTO;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\Users\Domain\Repository\UserRepositoryInterface;

class GetProfilesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /** @return array<ProfileDTO> */
    public function __invoke(GetProfilesQuery $query): array
    {
        $auth_user = $this->userFetcher->getAuthUser();
        $user = $this->userRepository->findById($auth_user->getId());

        $profiles = $this->profileRepository->findByUser($user, $query->page);

        $profileDTOs = [];

        foreach ($profiles as $profile)
        {
            $profileDTOs[] = ProfileDTO::fromEntity($profile);
        }

        return $profileDTOs;
    }
}
