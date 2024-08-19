<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatRole\UpdateRole;

use App\Shared\Application\Command\CommandInterface;

class UpdateRoleCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId,
        public readonly int $roleId,
        public readonly string $roleName,
        public readonly string $roleStyle,
        public readonly bool $roleCanRestrict,
    ) {
    }
}
