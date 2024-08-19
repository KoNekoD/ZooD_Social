<?php

declare(strict_types=1);

namespace App\Chats\Domain\Factory;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatMessage;
use App\Profiles\Domain\Entity\Profile;

class ChatMessageFactory
{
    public function create(Profile $from, Chat $chat, string $content): ChatMessage
    {
        return new ChatMessage($from, $chat, $content);
    }
}
