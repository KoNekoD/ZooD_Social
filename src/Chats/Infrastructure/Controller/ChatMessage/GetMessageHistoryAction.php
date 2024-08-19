<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\ChatMessage;

use App\Chats\Application\DTO\MessageByChatDTO;
use App\Chats\Application\Query\ChatMessage\FindMessagesByChat\FindMessagesByChatQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить историю сообщений')]
#[OA\Response(response: 200, description: 'Успешно', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: MessageByChatDTO::class))))]
#[OA\RequestBody(content: new Model(type: FindMessagesByChatQuery::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats/{chatId}/messages', name: 'api_chats_get_messages', methods: ['GET'])]
class GetMessageHistoryAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(string $chatId, Request $request): Response
    {
        $profileId = $request->query->get('profileId');
        $page = $request->query->getInt('page');

        /** @var MessageByChatDTO $messageDTOs */
        $messageDTOs = $this->queryBus->execute(new FindMessagesByChatQuery($profileId, $chatId, $page));

        return new Response($this->serializerService->serialize($messageDTOs));
    }
}
