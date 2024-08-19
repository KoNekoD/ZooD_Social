<?php

declare(strict_types=1);

namespace App\Chats\Domain\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatRole;

interface ChatRoleRepositoryInterface
{
    public function create(ChatRole $role): void;

    public function remove(ChatRole $role): void;

    /** @return array<ChatRole> */
    public function findByChat(Chat $chat): iterable;

    public function findOne(Chat $chat, int $roleId): ChatRole;

    public function save(): void;
}
