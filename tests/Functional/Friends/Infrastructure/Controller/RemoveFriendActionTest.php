<?php

declare(strict_types=1);

namespace App\Tests\Functional\Friends\Infrastructure\Controller;

use App\Friends\Domain\Entity\Friend;
use App\Tests\Resource\Fixture\Friend\FriendFixture;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveFriendActionTest extends WebTestCase
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
    public function test_friend_removed_successfully(): void
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

        // Remove friend
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $jwt1));
        $client->request('DELETE', '/api/friends/'.$profile1->getId().'/'.$profile2->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
