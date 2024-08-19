<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Friend;

use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;
use App\Friends\Domain\Factory\FriendFactory;
use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\Profile\ProfileSecondUserFixture;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FriendFixture extends Fixture implements DependentFixtureInterface
{
    use FakerTools;

    public const REFERENCE = 'friend';

    public function __construct(private readonly FriendFactory $friendFactory)
    {
    }

    /**
     * @throws UsingSharedUserException
     * @throws SelfFriendAddingException
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Profile $profile1 */
        $profile1 = $this->getReference(ProfileFixture::REFERENCE);
        /** @var Profile $profile2 */
        $profile2 = $this->getReference(ProfileSecondUserFixture::REFERENCE);

        $relation = Friend::RELATIONS_TARGET;

        $friend = $this->friendFactory->create($profile1, $profile2, $relation);

        $manager->persist($friend);
        $manager->flush();

        $this->addReference(self::REFERENCE, $friend);
    }

    public function getDependencies(): array
    {
        return [
            ProfileFixture::class,
            ProfileSecondUserFixture::class,
        ];
    }
}
