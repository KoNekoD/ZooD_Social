<?php

declare(strict_types=1);

namespace App\Chats\Domain\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatMessage;
use App\Profiles\Domain\Entity\Profile;

interface ChatMessageRepositoryInterface
{
    public function add(ChatMessage $message): void;

    public function remove(ChatMessage $message): void;

    /** @return array<ChatMessage> */
    public function findByChat(Chat $chat, int $page): iterable;

    /** @return array<ChatMessage> */
    public function findByProfile(Profile $from, int $page): iterable;

    /** @return array<ChatMessage> */
    public function findByProfileInChat(Profile $from, Chat $chat, int $page): iterable;

    public function findOneById(string $id): ChatMessage;

    public function save(): void;
}
