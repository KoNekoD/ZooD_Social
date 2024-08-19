<?php

declare(strict_types=1);

namespace App\Chats\Domain\Factory;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatRole;
use App\Chats\Domain\Specification\ChatRoleNameSpecification;
use App\Shared\Domain\Exception\ValidationException;

class ChatRoleFactory
{
    public function __construct(
        private readonly ChatRoleNameSpecification $chatRoleNameSpecification,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function create(
        Chat $chat,
        string $name,
        string $style,
        bool $creator,
        bool $canRestrict,
        bool $default
    ): ChatRole {
        return new ChatRole(
            $chat,
            $name,
            $style,
            $creator,
            $canRestrict,
            $default,
            $this->chatRoleNameSpecification
        );
    }
}
