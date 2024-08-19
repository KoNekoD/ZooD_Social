<?php

declare(strict_types=1);

namespace App\Profiles\Application\Query\FindProfile;

use App\Profiles\Application\DTO\ProfileDTO;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class FindProfileByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function __invoke(FindProfileByIdQuery $query): ProfileDTO
    {
        $profile = $this->profileRepository->findById($query->profileId);

        return ProfileDTO::fromEntity($profile);
    }
}
