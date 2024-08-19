<?php

declare(strict_types=1);

namespace App\Profiles\Application\Query\FindProfile;

use App\Shared\Application\Query\QueryInterface;

class FindProfileByIdQuery implements QueryInterface
{
    public function __construct(public readonly string $profileId)
    {
    }
}
