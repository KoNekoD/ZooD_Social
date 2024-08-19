<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class NotYourProfileException extends BaseException
{
    public function __construct(string $message = 'You attempted to interact with not your profile')
    {
        parent::__construct($message, self::PROFILES_NOT_YOUR_PROFILE);
    }

    public function getStatusCode(): int
    {
        return 403; // HTTP_FORBIDDEN
    }
}
