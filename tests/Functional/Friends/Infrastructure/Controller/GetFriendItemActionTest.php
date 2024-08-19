<?php

declare(strict_types=1);

namespace App\Tests\Functional\Friends\Infrastructure\Controller;

use App\Friends\Domain\Entity\Friend;
use App\Tests\Resource\Fixture\Friend\FriendFixture;
use App\Tests\Tools\FakerTools;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetFriendItemActionTest extends WebTestCase
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
    public function test_friend_found_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([FriendFixture::class]);

        /** @var Friend $friend */
        $friend = $executor->getReferenceRepository()->getReference(FriendFixture::REFERENCE);
        $user1 = $friend->getProfile()->getUser();
        $profile1 = $friend->getProfile();
        $profile2 = $friend->getFriend();

        // JWT1
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user1->getLogin(), 'password' => $user1->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $jwt1 = $data['token'];

        // Getting friend
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $jwt1));
        $client->jsonRequest('GET', '/api/friends/'.$profile1->getId().'/'.$profile2->getId());
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($friend->getRelationType(), $data['relationType']);

        // Getting reverted friend
        $client->jsonRequest('GET', '/api/friends/'.$profile2->getId().'/'.$profile1->getId());
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($friend->getRelationType() * -1, $data['relationType']);
    }
}
