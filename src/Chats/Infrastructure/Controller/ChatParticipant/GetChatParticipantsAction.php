<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\ChatParticipant;

use App\Chats\Application\DTO\ParticipantDTO;
use App\Chats\Application\Query\ChatParticipant\GetParticipants\GetParticipantsQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить список участников чата')]
#[OA\Response(response: 200, description: 'Успешно', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: ParticipantDTO::class))))]
#[OA\RequestBody(content: new Model(type: GetParticipantsQuery::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats/{chatId}/participants/{page}', name: 'api_messenger_chats_participants_get', methods: ['GET'])]
class GetChatParticipantsAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(string $chatId, int $page): Response
    {
        /** @var ParticipantDTO $participantDTOs */
        $participantDTOs = $this->queryBus->execute(new GetParticipantsQuery($chatId, $page));

        return new Response($this->serializerService->serialize($participantDTOs));
    }
}
