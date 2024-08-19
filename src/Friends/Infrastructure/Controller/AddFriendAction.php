<?php

declare(strict_types=1);

namespace App\Friends\Infrastructure\Controller;

use App\Friends\Application\Command\AddFriend\AddFriendCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Post(summary: 'Добавить друга')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\Tag(name: 'Friends')]
#[Security(name: 'Bearer')]
#[Route('/api/friends/{senderProfileId}/{destinationProfileId}', name: 'api_friends_add', methods: ['POST'])]
class AddFriendAction
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function __invoke(string $senderProfileId, string $destinationProfileId): Response
    {
        $this->commandBus->execute(new AddFriendCommand($senderProfileId, $destinationProfileId));

        return new Response();
    }
}
