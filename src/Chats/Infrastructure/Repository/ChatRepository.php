<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Exception\ChatNotFoundException;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function create(Chat $chat): void
    {
        $this->_em->persist($chat);
        $this->_em->flush();
    }

    public function remove(Chat $chat): void
    {
        $this->_em->remove($chat);
        $this->_em->flush();
    }

    /**
     * @throws ChatNotFoundException
     */
    public function findById(string $id): Chat
    {
        $chat = $this->find($id);

        if (null === $chat)
        {
            throw new ChatNotFoundException();
        }

        return $chat;
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
