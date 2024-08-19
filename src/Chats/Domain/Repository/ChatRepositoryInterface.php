<?php

declare(strict_types=1);

namespace App\Chats\Domain\Repository;

use App\Chats\Domain\Entity\Chat;

interface ChatRepositoryInterface
{
    public function create(Chat $chat): void;

    public function remove(Chat $chat): void;

    public function findById(string $id): Chat;

    public function save(): void;
}
