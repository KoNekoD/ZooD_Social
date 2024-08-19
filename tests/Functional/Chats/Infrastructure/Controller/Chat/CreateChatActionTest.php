<?php

declare(strict_types=1);

namespace App\Tests\Functional\Chats\Infrastructure\Controller\Chat;

use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Profile\ProfileSecondUserFixture;
use App\Tests\Resource\Fixture\User\SecondUserFixture;
use App\Tests\Tools\FakerTools;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateChatActionTest extends WebTestCase
{
    use FakerTools;

    private AbstractDatabaseTool $databaseTool;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * @throws Exception
     */
    public function test_chat_created_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ProfileSecondUserFixture::class]);

        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(SecondUserFixture::REFERENCE);

        /** @var Profile $profile */
        $profile = $executor->getReferenceRepository()->getReference(ProfileSecondUserFixture::REFERENCE);

        // Arrange
        /* Getting JWT token */
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user->getLogin(), 'password' => $user->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));
        /* --- JWT --- * */

        // Act
        $client->jsonRequest('POST', '/api/chats',
            [
                'chatTitle' => $this->getFaker()->title(),
                'chatDescription' => ' ',
                'creatorProfileId' => $profile->getId(),
            ]
        );

        // Assert
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
