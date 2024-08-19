<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Service;

use App\Profiles\Domain\Entity\Profile;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Domain\Security\UserFetcherInterface;

class ProfileAuthenticatorService
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /**
     * @throws NotYourProfileException
     */
    public function authenticate(string $profileId): Profile
    {
        $auth_user = $this->userFetcher->getAuthUser();
        $profile = $this->profileRepository->findById($profileId);

        // Checks profile owner
        if ($auth_user->getId() !== $profile->getUser()->getId())
        {
            throw new NotYourProfileException();
        }

        return $profile;
    }
}
