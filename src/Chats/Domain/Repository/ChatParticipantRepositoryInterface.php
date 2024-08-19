<?php

declare(strict_types=1);

namespace App\Chats\Domain\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatParticipant;
use App\Profiles\Domain\Entity\Profile;

interface ChatParticipantRepositoryInterface
{
    public function add(ChatParticipant $participant): void;

    public function remove(ChatParticipant $participant): void;

    /** @return array<ChatParticipant> */
    public function findByChat(Chat $chat, int $page): iterable;

    /** @return array<ChatParticipant> */
    public function findByProfile(Profile $profile, int $page): iterable;

    public function findOne(Profile $profile, Chat $chat): ChatParticipant;

    public function isExist(Profile $profile, Chat $chat): bool;

    public function save(): void;
}
