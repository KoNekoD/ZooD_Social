<?php

declare(strict_types=1);

namespace App\Friends\Infrastructure\Controller;

use App\Friends\Application\DTO\FriendDTO;
use App\Friends\Application\Query\GetFriend\GetFriendQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить информацию о дружбе')]
#[OA\Response(response: 200, description: 'Успешно', content: new Model(type: FriendDTO::class))]
#[OA\Tag(name: 'Friends')]
#[Security(name: 'Bearer')]
#[Route('/api/friends/{profileId}/{friendId}', name: 'api_friends_item', methods: ['GET'])]
class GetFriendItemAction
{
    public function __construct(private readonly QueryBusInterface $queryBus, private readonly SerializerServiceInterface $serializerService)
    {
    }

    public function __invoke(string $profileId, string $friendId): Response
    {
        /** @var FriendDTO $friendDTO */
        $friendDTO = $this->queryBus->execute(new GetFriendQuery($profileId, $friendId));

        $result = $this->serializerService->serialize($friendDTO);

        return new Response($result);
    }
}
