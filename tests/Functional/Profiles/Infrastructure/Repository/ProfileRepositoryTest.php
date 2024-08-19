<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profiles\Infrastructure\Repository;

use App\Profiles\Domain\Exception\ProfileNotFoundException;
use App\Profiles\Infrastructure\Repository\ProfileRepository;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\User\UserFixture;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileRepositoryTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private ProfileRepository $profileRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->profileRepository = static::getContainer()->get(ProfileRepository::class);
    }

    /**
     * @throws ProfileNotFoundException
     */
    public function test_profiles_created_and_found_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([ProfileFixture::class]);

        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        $items = $this->profileRepository->findByUser($user, 1);

        $this->assertNotEmpty($items);
    }
}
