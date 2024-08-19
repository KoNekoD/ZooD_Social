<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\ChatParticipant;

use App\Chats\Application\Command\ChatParticipant\SendInvite\SendInviteCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Post(summary: 'Пригласить в чат')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\RequestBody(content: new Model(type: SendInviteCommand::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats/invite', name: 'api_messenger_chats_invite', methods: ['POST'])]
class SendInviteAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var SendInviteCommand $sendInviteCommand */
        $sendInviteCommand = $this->serializerService->deserialize($request->getContent(), SendInviteCommand::class);

        $this->commandBus->execute($sendInviteCommand);

        return new Response();
    }
}
