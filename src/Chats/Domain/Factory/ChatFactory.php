<?php

declare(strict_types=1);

namespace App\Chats\Domain\Factory;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Specification\Chat\ChatSpecification;
use App\Shared\Domain\Exception\ValidationException;

class ChatFactory
{
    public function __construct(
        private readonly ChatSpecification $chatSpecification
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function create(string $title, string $description): Chat
    {
        return new Chat($title, $description, $this->chatSpecification);
    }
}
