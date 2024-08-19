<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatMessage;
use App\Chats\Domain\Exception\MessageNotFoundException;
use App\Chats\Domain\Repository\ChatMessageRepositoryInterface;
use App\Profiles\Domain\Entity\Profile;
use App\Shared\Domain\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class ChatMessageRepository extends ServiceEntityRepository implements ChatMessageRepositoryInterface
{
    private PaginatorService $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorService $paginator)
    {
        parent::__construct($registry, ChatMessage::class);
        $this->paginator = $paginator;
    }

    public function add(ChatMessage $message): void
    {
        $this->_em->persist($message);
        $this->_em->flush();
    }

    public function remove(ChatMessage $message): void
    {
        $this->_em->remove($message);
        $this->_em->flush();
    }

    /**
     * @return array<ChatMessage>
     *
     * @throws MessageNotFoundException
     */
    public function findByChat(Chat $chat, int $page): iterable
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.chat = :chat')
            ->setParameter('chat', $chat)
            ->getQuery();

        $items = $this->paginator->paginate($query, $page, ChatMessage::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new MessageNotFoundException();
        }

        return $items;
    }

    /**
     * @return array<ChatMessage>
     *
     * @throws MessageNotFoundException
     */
    public function findByProfile(Profile $from, int $page): iterable
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.from = :from')
            ->setParameter('from', $from)
            ->getQuery();

        $items = $this->paginator->paginate($query, $page, ChatMessage::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new MessageNotFoundException();
        }

        return $items;
    }

    /**
     * @return array<ChatMessage>
     *
     * @throws MessageNotFoundException
     */
    public function findByProfileInChat(Profile $from, Chat $chat, int $page): iterable
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.from = :from')
            ->andWhere('m.chat = :chat')
            ->setParameters(['from' => $from, 'chat' => $chat])
            ->getQuery();

        $items = $this->paginator->paginate($query, $page, ChatMessage::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new MessageNotFoundException();
        }

        return $items;
    }

    /**
     * @throws NonUniqueResultException
     * @throws MessageNotFoundException
     */
    public function findOneById(string $id): ChatMessage
    {
        try
        {
            return $this->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            throw new MessageNotFoundException();
        }
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
