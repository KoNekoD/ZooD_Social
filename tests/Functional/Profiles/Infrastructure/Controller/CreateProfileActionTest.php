<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profiles\Infrastructure\Controller;

use App\Tests\Resource\Fixture\User\SecondUserFixture;
use App\Tests\Tools\FakerTools;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateProfileActionTest extends WebTestCase
{
    use FakerTools;

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
    public function test_profile_created_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([SecondUserFixture::class]);

        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(SecondUserFixture::REFERENCE);

        // JWT
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user->getLogin(), 'password' => $user->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        $profileData = ['firstName' => $this->getFaker()->firstName()];

        // act
        $client->jsonRequest('POST', '/api/profiles', $profileData);

        // assert
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->Request('GET', '/api/profiles');
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // assert
        $this->assertEquals($profileData['firstName'], $data[0]['firstName']);
    }
}
