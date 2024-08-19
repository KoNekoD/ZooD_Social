<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\Chat;

use App\Chats\Application\DTO\ChatDTO;
use App\Chats\Application\Query\Chat\FindChat\FindChatByIdQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить информацию о чате')]
#[OA\Response(response: 200, description: 'Успешно', content: new Model(type: ChatDTO::class))]
#[OA\RequestBody(content: new Model(type: FindChatByIdQuery::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats/{chatId}', name: 'api_messenger_chats_find', methods: ['GET'])]
class FindChatAction
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly SerializerServiceInterface $serializerService,
    ) {
    }

    public function __invoke(string $chatId): Response
    {
        /** @var ChatDTO $chatDTO */
        $chatDTO = $this->queryBus->execute(new FindChatByIdQuery($chatId));

        // Json encoded DTO
        $result = $this->serializerService->serialize($chatDTO);

        return new Response($result);
    }
}
