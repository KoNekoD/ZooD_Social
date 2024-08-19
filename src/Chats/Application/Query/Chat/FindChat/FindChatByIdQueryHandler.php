<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\Chat\FindChat;

use App\Chats\Application\DTO\ChatDTO;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class FindChatByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
    ) {
    }

    public function __invoke(FindChatByIdQuery $query): ChatDTO
    {
        $chat = $this->chatRepository->findById($query->chatId);

        return ChatDTO::fromEntity($chat);
    }
}
