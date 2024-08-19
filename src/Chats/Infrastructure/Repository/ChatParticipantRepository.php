<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Exception\ParticipantNotFoundException;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Profiles\Domain\Entity\Profile;
use App\Shared\Domain\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class ChatParticipantRepository extends ServiceEntityRepository implements ChatParticipantRepositoryInterface
{
    private PaginatorService $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorService $paginator)
    {
        parent::__construct($registry, ChatParticipant::class);
        $this->paginator = $paginator;
    }

    public function add(ChatParticipant $participant): void
    {
        $this->_em->persist($participant);
        $this->_em->flush();
    }

    public function remove(ChatParticipant $participant): void
    {
        $this->_em->remove($participant);
        $this->_em->flush();
    }

    /**
     * @throws ParticipantNotFoundException
     */
    public function findByChat(Chat $chat, int $page): iterable
    {
        $query = $this->createQueryBuilder('p')
           ->where('p.chat = :chat')
           ->setParameter('chat', $chat)
           ->getQuery();

        $items = $this->paginator->paginate($query, $page, ChatParticipant::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new ParticipantNotFoundException();
        }

        return $items;
    }

    /**
     * @throws ParticipantNotFoundException
     */
    public function findByProfile(Profile $profile, int $page): iterable
    {
        // Girl security
        if ($page < 1)
        {
            $page = 1;
        }

        $query = $this->createQueryBuilder('p')
            ->where('p.profile = :profile')
            ->setParameter('profile', $profile)
            ->getQuery();

        $items = $this->paginator->paginate($query, $page, Chat::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new ParticipantNotFoundException();
        }

        return $items;
    }

    /**
     * @throws NonUniqueResultException
     * @throws ParticipantNotFoundException
     */
    public function findOne(Profile $profile, Chat $chat): ChatParticipant
    {
        try
        {
            return $this->createQueryBuilder('p')
                ->where('p.profile = :profile')
                ->andWhere('p.chat = :chat')
                ->setParameters([
                    'profile' => $profile,
                    'chat' => $chat,
                ])
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            throw new ParticipantNotFoundException();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function isExist(Profile $profile, Chat $chat): bool
    {
        try
        {
            $this->createQueryBuilder('p')
                ->where('p.profile = :profile')
                ->andWhere('p.chat = :chat')
                ->setParameters([
                    'profile' => $profile,
                    'chat' => $chat,
                ])
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            return false;
        }

        return true;
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
