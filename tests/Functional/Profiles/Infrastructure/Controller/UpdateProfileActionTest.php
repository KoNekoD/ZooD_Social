<?php

declare(strict_types=1);

namespace App\Tests\Functional\Profiles\Infrastructure\Controller;

use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\User\UserFixture;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateProfileActionTest extends WebTestCase
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
    public function test_profile_updated_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $executor = $this->databaseTool->loadFixtures([ProfileFixture::class]);

        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        /** @var Profile $profile */
        $profile = $executor->getReferenceRepository()->getReference(ProfileFixture::REFERENCE);

        $client->jsonRequest(
            'POST',
            '/api/auth/token/login',
            [
                'login' => $user->getLogin(),
                'password' => $user->getPassword(),
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // act
        $new_data = [
            'profileId' => $profile->getId(),
            'firstName' => 'Joe',
        ];
        $client->jsonRequest('PUT', '/api/profiles', $new_data);

        // assert
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->Request('GET', '/api/profiles/'.$profile->getId());

        $new_profile = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // assert
        $this->assertEquals('Joe', $new_profile['firstName']);
    }
}
