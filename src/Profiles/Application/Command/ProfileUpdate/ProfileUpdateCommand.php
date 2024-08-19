<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\ProfileUpdate;

use App\Shared\Application\Command\CommandInterface;

class ProfileUpdateCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $firstName,
        public readonly ?string $lastName,
    ) {
    }
}
