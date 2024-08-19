<?php

declare(strict_types=1);

namespace App\Chats\Domain\Entity;

use App\Profiles\Domain\Entity\Profile;
use App\Shared\Domain\Service\UlidService;

class ChatMessage
{
    private string $id;
    private Profile $from;
    private Chat $chat;
    private string $content;

    public function __construct(Profile $from, Chat $chat, string $content)
    {
        $this->id = UlidService::generate();
        $this->from = $from;
        $this->chat = $chat;
        $this->content = $content;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): Profile
    {
        return $this->from;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function editMessage(string $content): void
    {
        $this->content = $content;
    }

    public const PER_PAGE_LIMIT = 100;
}
