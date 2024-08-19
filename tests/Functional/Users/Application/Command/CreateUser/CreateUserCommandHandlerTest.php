<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Infrastructure\Bus\CommandBus;
use App\Users\Application\Command\CreateUser\CreateUserCommand;
use App\Users\Domain\Exception\UserNotFoundException;
use App\Users\Domain\Repository\UserRepositoryInterface;
use App\Users\Infrastructure\Repository\UserRepository;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserCommandHandlerTest extends WebTestCase
{
    private Generator $faker;
    private CommandBus $commandBus;
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->commandBus = $this::getContainer()->get(CommandBusInterface::class);
        $this->userRepository = $this::getContainer()->get(UserRepositoryInterface::class);
    }

    /**
     * @throws UserNotFoundException
     */
    public function test_user_created_successfully(): void
    {
        $command = new CreateUserCommand($this->faker->userName(), $this->faker->password());

        // act
        $userId = $this->commandBus->execute($command);

        // assert
        $user = $this->userRepository->findById($userId);
        $this->assertNotEmpty($user);
    }
}
