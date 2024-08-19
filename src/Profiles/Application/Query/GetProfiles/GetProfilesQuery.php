<?php

declare(strict_types=1);

namespace App\Profiles\Application\Query\GetProfiles;

use App\Shared\Application\Query\QueryInterface;

class GetProfilesQuery implements QueryInterface
{
    public function __construct(public readonly int $page)
    {
    }
}
