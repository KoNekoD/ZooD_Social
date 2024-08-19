<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users\Application\Query\FindUserByLogin;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Bus\QueryBus;
use App\Tests\Resource\Fixture\User\SecondUserFixture;
use App\Users\Application\DTO\UserDTO;
use App\Users\Application\Query\FindUserByLogin\FindUserByLoginQuery;
use App\Users\Domain\Entity\User;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindUserByLoginQueryHandlerTest extends WebTestCase
{
    private QueryBus $queryBus;
    private AbstractDatabaseTool $databaseTool;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = $this::getContainer()->get(QueryBusInterface::class);
        $this->databaseTool = $this::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_user_created_when_command_executed(): void
    {
        // arrange
        $referenceRepository = $this->databaseTool->loadFixtures([SecondUserFixture::class])->getReferenceRepository();

        /** @var User $user */
        $user = $referenceRepository->getReference(SecondUserFixture::REFERENCE);
        $query = new FindUserByLoginQuery($user->getLogin());

        // act
        $userDTO = $this->queryBus->execute($query);

        // assert
        $this->assertInstanceOf(UserDTO::class, $userDTO);
    }
}
