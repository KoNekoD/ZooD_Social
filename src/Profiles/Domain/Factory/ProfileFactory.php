<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Factory;

use App\Profiles\Domain\Entity\Profile;
use App\Users\Domain\Entity\User;

class ProfileFactory
{
    public function create(User $user, string $firstName, ?string $lastName): Profile
    {
        return new Profile($user, $firstName, $lastName);
    }
}
