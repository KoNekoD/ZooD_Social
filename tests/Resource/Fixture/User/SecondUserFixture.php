<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\User;

use App\Tests\Tools\FakerTools;
use App\Users\Domain\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SecondUserFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'second_user';

    public function __construct(private readonly UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $login = $this->getFaker()->userName();

        $password = $this->getFaker()->password();
        $user = $this->userFactory->create($login, $password);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}
