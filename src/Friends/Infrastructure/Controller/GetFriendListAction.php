<?php

declare(strict_types=1);

namespace App\Friends\Infrastructure\Controller;

use App\Friends\Application\DTO\FriendDTO;
use App\Friends\Application\Query\GetFriends\FindFriendsByProfileQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(description: 'Дружба это не только друг, это также входящий/исходящий запрос в друзья', summary: 'Получить список дружб*')]
#[OA\Response(response: 200, description: 'Успешно', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: FriendDTO::class))))]
#[OA\Parameter(name: 'page', in: 'query', example: 1)]
#[OA\Parameter(name: 'relationType', in: 'query', example: 1)]
#[OA\Tag(name: 'Friends')]
#[Security(name: 'Bearer')]
#[Route('/api/friends/{profileId}', name: 'api_friends_list', methods: ['GET'])]
class GetFriendListAction
{
    public function __construct(private readonly QueryBusInterface $queryBus, private readonly SerializerServiceInterface $serializerService)
    {
    }

    public function __invoke(Request $request, string $profileId): Response
    {
        $page = $request->query->getInt('page', 1);
        $relationType = $request->query->getInt('relationType', 1);

        /** @var FriendDTO[] $friendDTOs */
        $friendDTOs = $this->queryBus->execute(new FindFriendsByProfileQuery($profileId, $relationType, $page));

        return new Response($this->serializerService->serialize($friendDTOs));
    }
}
