<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Repository;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatRole;
use App\Chats\Domain\Exception\RoleNotFoundException;
use App\Chats\Domain\Repository\ChatRoleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionBase;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class ChatRoleRepository extends ServiceEntityRepository implements ChatRoleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatRole::class);
    }

    public function create(ChatRole $role): void
    {
        $this->_em->persist($role);
        $this->_em->flush();
    }

    /**
     * @throws RoleNotFoundException
     */
    public function findByChat(Chat $chat): array
    {
        /** @var array<ChatRole> $result */
        $result = $this->createQueryBuilder('cr')
            ->where('cr.chat = :chat')
            ->setParameter('chat', $chat)
            ->setMaxResults(512)
            ->getQuery()
            ->getResult();

        if (empty($result))
        {
            throw new RoleNotFoundException();
        }

        return $result;
    }

    /**
     * @throws RoleNotFoundException
     * @throws NonUniqueResultExceptionBase
     */
    public function findOne(Chat $chat, int $roleId): ChatRole
    {
        try
        {
            return $this->createQueryBuilder('cr')
                ->where('cr.chat = :chat')
                ->andWhere('cr.id = :roleId')
                ->setParameters(['chat' => $chat, 'roleId' => $roleId])
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            throw new RoleNotFoundException();
        }
    }

    public function remove(ChatRole $role): void
    {
        $this->_em->remove($role);
        $this->_em->flush();
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
