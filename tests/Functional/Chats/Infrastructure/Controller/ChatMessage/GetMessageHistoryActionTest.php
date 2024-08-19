<?php

declare(strict_types=1);

namespace App\Tests\Functional\Chats\Infrastructure\Controller\ChatMessage;

use App\Chats\Domain\Entity\Chat;
use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Chat\ChatFixture;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Tools\FakerTools;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetMessageHistoryActionTest extends WebTestCase
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
    public function test_chat_messages_returned_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ChatFixture::class]);

        /** @var Chat $chat */
        $chat = $executor->getReferenceRepository()->getReference(ChatFixture::REFERENCE);
        /** @var Profile $profile */
        $profile = $executor->getReferenceRepository()->getReference(ProfileFixture::REFERENCE);
        $user = $profile->getUser();

        /* Getting JWT token */
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user->getLogin(), 'password' => $user->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // Getting messages
        $client->request('GET', '/api/chats/'.$chat->getId().'/messages', ['profileId' => $profile->getId(), 'page' => 1]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($data);
    }
}
