<?php

declare(strict_types=1);

namespace App\Tests\Functional\Friends\Infrastructure\Controller;

use App\Friends\Domain\Entity\Friend;
use App\Profiles\Domain\Entity\Profile;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Resource\Fixture\Profile\ProfileSecondUserFixture;
use App\Tests\Resource\Fixture\User\SecondUserFixture;
use App\Tests\Resource\Fixture\User\UserFixture;
use App\Tests\Tools\FakerTools;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddFriendActionTest extends WebTestCase
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
    public function test_friend_added_successfully(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        // Profile 1, 2
        $executor = $this->databaseTool->loadFixtures([ProfileFixture::class, ProfileSecondUserFixture::class]);

        /** @var User $user1 */
        $user1 = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);
        /** @var Profile $profile1 */
        $profile1 = $executor->getReferenceRepository()->getReference(ProfileFixture::REFERENCE);
        /** @var User $user2 */
        $user2 = $executor->getReferenceRepository()->getReference(SecondUserFixture::REFERENCE);
        /** @var Profile $profile2 */
        $profile2 = $executor->getReferenceRepository()->getReference(ProfileSecondUserFixture::REFERENCE);

        // JWT1
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user1->getLogin(), 'password' => $user1->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $jwt1 = $data['token'];

        // JWT2
        $client->jsonRequest('POST', '/api/auth/token/login', ['login' => $user2->getLogin(), 'password' => $user2->getPassword()]);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $jwt2 = $data['token'];

        // [F -> F]Set active user 1
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $jwt1));
        $client->request('POST', '/api/friends/'.$profile1->getId().'/'.$profile2->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // [F <-> F] Set active user 2
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $jwt2));
        $client->request('POST', '/api/friends/'.$profile2->getId().'/'.$profile1->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check MUTUAL(F<->F) friend type
        $client->jsonRequest('GET', '/api/friends/'.$profile2->getId().'/'.$profile1->getId());
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals(Friend::RELATION_MUTUAL, $data['relationType']);
    }
}
