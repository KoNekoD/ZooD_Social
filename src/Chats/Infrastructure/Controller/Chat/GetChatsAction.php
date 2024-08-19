<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\Chat;

use App\Chats\Application\DTO\ChatDTO;
use App\Chats\Application\Query\Chat\GetChats\GetChatsQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить список чатов, в которых состоит профиль')]
#[OA\Response(response: 200, description: 'Успешно', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: ChatDTO::class))))]
#[OA\RequestBody(content: new Model(type: GetChatsQuery::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats', name: 'api_messenger_chats_get', methods: ['GET'])]
class GetChatsAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var GetChatsQuery $getChatsQuery */
        $getChatsQuery = $this->serializerService->denormalize($request->query->all(), GetChatsQuery::class);

        /** @var ChatDTO[] $chatDTOs */
        $chatDTOs = $this->queryBus->execute($getChatsQuery);

        return new Response($this->serializerService->serialize($chatDTOs));
    }
}
