<?php

declare(strict_types=1);

namespace App\Tests\Functional\Chats\Infrastructure\Controller\ChatParticipant;

use App\Chats\Domain\Entity\Chat;
use App\Chats\Domain\Entity\ChatRole;
use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Chat\ChatFixture;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\Profile\ProfileSecondUserFixture;
use App\Tests\Tools\FakerTools;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SendInviteActionTest extends WebTestCase
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
    public function test_profile_invited_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ChatFixture::class, ProfileSecondUserFixture::class]);

        /** @var Chat $chat */
        $chat = $executor->getReferenceRepository()->getReference(ChatFixture::REFERENCE);
        /** @var ChatRole $role_member */
        $role_member = $executor->getReferenceRepository()->getReference(ChatFixture::REFERENCE_ROLE_MEMBER);
        /** @var Profile $profile */
        $profile = $executor->getReferenceRepository()->getReference(ProfileFixture::REFERENCE);
        /** @var Profile $profileSecondUser */
        $profileSecondUser = $executor->getReferenceRepository()->getReference(ProfileSecondUserFixture::REFERENCE);
        $user = $profile->getUser();

        /* Getting JWT token */
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user->getLogin(), 'password' => $user->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // Inviting profile
        $client->jsonRequest('POST', '/api/chats/invite',
            [
                'chatId' => $chat->getId(),
                'senderProfileId' => $profile->getId(),
                'destinationProfileId' => $profileSecondUser->getId(),
                'roleId' => $role_member->getId(),
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
