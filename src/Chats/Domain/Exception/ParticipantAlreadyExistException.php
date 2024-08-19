<?php

declare(strict_types=1);

namespace App\Chats\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class ParticipantAlreadyExistException extends BaseException
{
    public function __construct(string $message = 'Participant already exist')
    {
        parent::__construct($message, self::CHATS_PARTICIPANT_ALREADY_EXIST);
    }

    public function getStatusCode(): int
    {
        return 400; // Bad request
    }
}
