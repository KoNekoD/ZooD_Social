<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Profile;

use App\Profiles\Domain\Factory\ProfileFactory;
use App\Tests\Resource\Fixture\User\SecondUserFixture;
use App\Tests\Tools\FakerTools;
use App\Users\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProfileSecondUserFixture extends Fixture implements DependentFixtureInterface
{
    use FakerTools;

    public const REFERENCE = 'profile_second_user';

    public function __construct(
        private readonly ProfileFactory $profileFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(SecondUserFixture::REFERENCE);

        $profile = $this->profileFactory->create(
            $user,
            $this->getFaker()->firstName(),
            $this->getFaker()->lastName(),
        );

        $manager->persist($profile);
        $manager->flush();

        $this->addReference(self::REFERENCE, $profile);
    }

    public function getDependencies(): array
    {
        return [
            SecondUserFixture::class,
        ];
    }
}
