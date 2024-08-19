<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profiles\Infrastructure\Controller;

use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\User\UserFixture;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindProfileActionTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * @throws Exception
     */
    public function test_profile_found_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ProfileFixture::class]);

        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        /** @var Profile $profile */
        $profile = $executor->getReferenceRepository()->getReference(ProfileFixture::REFERENCE);

        // JWT
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user->getLogin(), 'password' => $user->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // act
        $client->request('GET', '/api/profiles/'.$profile->getId());

        // assert
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // assert
        $this->assertEquals($profile->getFirstName(), $data['firstName']);
    }
}
