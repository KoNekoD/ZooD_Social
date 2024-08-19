<?php

declare(strict_types=1);

namespace App\Friends\Infrastructure\Repository;

use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Exception\FriendNotFoundException;
use App\Friends\Domain\Repository\FriendRepositoryInterface;
use App\Profiles\Domain\Entity\Profile;
use App\Shared\Domain\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class FriendRepository extends ServiceEntityRepository implements FriendRepositoryInterface
{
    private PaginatorService $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorService $paginator)
    {
        parent::__construct($registry, Friend::class);
        $this->paginator = $paginator;
    }

    public function addFriend(Friend $friend): void
    {
        $this->_em->persist($friend);
        $this->_em->flush();
    }

    public function removeFriend(Friend $friend): void
    {
        $this->_em->remove($friend);
        $this->_em->flush();
    }

    public function save(): void
    {
        $this->_em->flush();
    }

    /**
     * @throws NonUniqueResultException
     * @throws FriendNotFoundException
     */
    public function findOne(Profile $profile, Profile $friend): Friend
    {
        try
        {
            return $this->createQueryBuilder('f')
                ->where('(f.profile = :profile AND f.friend = :friend) 
                    OR
                (f.profile = :friend AND f.friend = :profile)'
                )
                ->setParameters(['profile' => $profile, 'friend' => $friend])
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            throw new FriendNotFoundException();
        }
    }

    /**
     * @throws FriendNotFoundException
     */
    public function findFriend(Profile $profile, int $relationType, int $page): iterable
    {
        $query = $this->createQueryBuilder('f')
            ->where('(f.profile = :profile AND f.relationType = :relationType) 
                OR 
            (f.friend = :profile AND f.relationType = :inverseRelationType)'
            )
            ->setParameters(['profile' => $profile, 'relationType' => $relationType, 'inverseRelationType' => $relationType * -1])
            ->getQuery();

        $items = $this->paginator->paginateNative($query, $page, Friend::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new FriendNotFoundException();
        }

        return $items;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function isExist(Profile $profile, Profile $friend): bool
    {
        try
        {
            $this->createQueryBuilder('f')
                ->where('(f.profile = :profile AND f.friend = :friend) 
                    OR 
                (f.profile = :friend AND f.friend = :profile)'
                )
                ->setParameters(['profile' => $profile, 'friend' => $friend])
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException)
        {
            return false;
        }

        return true;
    }
}
