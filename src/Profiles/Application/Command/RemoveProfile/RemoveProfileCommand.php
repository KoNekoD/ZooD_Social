<?php

declare(strict_types=1);

namespace App\Profiles\Application\Command\RemoveProfile;

use App\Shared\Application\Command\CommandInterface;

class RemoveProfileCommand implements CommandInterface
{
    public function __construct(public readonly string $profileId)
    {
    }
}
