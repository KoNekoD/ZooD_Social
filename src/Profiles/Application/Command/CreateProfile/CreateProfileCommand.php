<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\CreateProfile;

use App\Shared\Application\Command\CommandInterface;

class CreateProfileCommand implements CommandInterface
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $lastName,
    ) {
    }
}
