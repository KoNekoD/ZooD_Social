<?php

declare(strict_types=1);

namespace App\Chats\Application\DTO;

use App\Chats\Domain\Entity\ChatMessage;

class MessageByChatDTO
{
    public string $id;
    public string $from;
    public string $content;

    public function __construct(string $id, string $from, string $content)
    {
        $this->id = $id;
        $this->from = $from;
        $this->content = $content;
    }

    public static function fromEntity(ChatMessage $message): self
    {
        return new self(
            $message->getId(),
            $message->getFrom()->getId(),
            $message->getContent(),
        );
    }
}
