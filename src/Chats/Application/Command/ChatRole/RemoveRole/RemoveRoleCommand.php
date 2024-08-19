<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatRole\RemoveRole;

use App\Shared\Application\Command\CommandInterface;

class RemoveRoleCommand implements CommandInterface
{
    public function __construct(
        public readonly string $profileId,
        public readonly string $chatId,
        public readonly int $roleId,
    ) {
    }
}
