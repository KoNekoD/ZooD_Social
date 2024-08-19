<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class ProfileNotFoundException extends BaseException
{
    public function __construct(string $message = 'Profile not found')
    {
        parent::__construct($message, self::PROFILES_PROFILE_NOT_FOUND);
    }

    public function getStatusCode(): int
    {
        return 404;
    }
}
