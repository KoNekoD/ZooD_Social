<?php

declare(strict_types=1);

namespace App\Chats\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class YouDoesNotCanRestrictException extends BaseException
{
    public function __construct(string $message = 'You does not have restrict rights')
    {
        parent::__construct($message, self::CHATS_ROLE_NOT_CAN_RESTRICT);
    }

    public function getStatusCode(): int
    {
        return 403;
    }
}
