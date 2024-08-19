<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Repository;

use App\Profiles\Domain\Entity\Profile;
use App\Users\Domain\Entity\User;

interface ProfileRepositoryInterface
{
    public function create(Profile $profile): void;

    public function remove(Profile $profile): void;

    public function findById(string $profileId): Profile;

    /** @return Profile[] */
    public function findByUser(User $user, int $page): iterable;

    public function save(): void;
}
