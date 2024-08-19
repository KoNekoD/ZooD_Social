<?php

declare(strict_types=1);

namespace App\Profiles\Infrastructure\Repository;

use App\Profiles\Domain\Entity\Profile;
use App\Profiles\Domain\Exception\ProfileNotFoundException;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProfileRepository extends ServiceEntityRepository implements ProfileRepositoryInterface
{
    private PaginatorService $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorService $paginatorService)
    {
        parent::__construct($registry, Profile::class);

        $this->paginator = $paginatorService;
    }

    public function create(Profile $profile): void
    {
        $this->_em->persist($profile);
        $this->_em->flush();
    }

    /**
     * @throws ProfileNotFoundException
     */
    public function findById(string $profileId): Profile
    {
        $profile = $this->find($profileId);

        if (null === $profile)
        {
            throw new ProfileNotFoundException();
        }

        return $profile;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ProfileNotFoundException
     */
    public function findByUser(AuthUserInterface $user, int $page): iterable
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $items = $this->paginator->paginate($query, $page, Profile::PER_PAGE_LIMIT);

        if (0 === count($items))
        {
            throw new ProfileNotFoundException();
        }

        return $items;
    }

    public function save(): void
    {
        $this->_em->flush();
    }

    public function remove(Profile $profile): void
    {
        $this->_em->remove($profile);

        $this->_em->flush();
    }
}
