<?php

declare(strict_types=1);

namespace App\Tests\Functional\Chats\Infrastructure\Controller\ChatParticipant;

use App\Chats\Domain\Entity\Chat;
use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Chat\ChatSecondaryUserProfileHasInvitedFixture;
use App\Tests\Resource\Fixture\Profile\ProfileSecondUserFixture;
use App\Tests\Tools\FakerTools;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HandleInviteActionTest extends WebTestCase
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
    public function test_invite_accepted_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ChatSecondaryUserProfileHasInvitedFixture::class]);

        /** @var Chat $chat */
        $chat = $executor->getReferenceRepository()->getReference(ChatSecondaryUserProfileHasInvitedFixture::REFERENCE);
        /** @var Profile $profile2 */
        $profile2 = $executor->getReferenceRepository()->getReference(ProfileSecondUserFixture::REFERENCE);
        $user2 = $profile2->getUser();

        /* Getting JWT token for secondary user */
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user2->getLogin(), 'password' => $user2->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // Accept invite
        $client->jsonRequest('POST', '/api/chats/invite/handle', ['profileId' => $profile2->getId(), 'chatId' => $chat->getId()]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function test_invite_rejected_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ChatSecondaryUserProfileHasInvitedFixture::class]);

        /** @var Chat $chat */
        $chat = $executor->getReferenceRepository()->getReference(ChatSecondaryUserProfileHasInvitedFixture::REFERENCE);
        /** @var Profile $profile2 */
        $profile2 = $executor->getReferenceRepository()->getReference(ProfileSecondUserFixture::REFERENCE);
        $user2 = $profile2->getUser();

        /* Getting JWT token for secondary user */
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user2->getLogin(), 'password' => $user2->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // Reject invite
        $client->jsonRequest('DELETE', '/api/chats/invite/handle', ['profileId' => $profile2->getId(), 'chatId' => $chat->getId()]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
