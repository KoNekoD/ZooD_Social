<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\ChatParticipant;

use App\Chats\Application\Command\ChatParticipant\AcceptInvite\AcceptInviteCommand;
use App\Chats\Application\Command\ChatParticipant\RejectInvite\RejectInviteCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HandleInviteAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    #[OA\Post(summary: 'Принять приглашение в чат')]
    #[OA\Response(response: 200, description: 'Успешно')]
    #[OA\RequestBody(content: new Model(type: AcceptInviteCommand::class))]
    #[OA\Tag(name: 'Chats')]
    #[Security(name: 'Bearer')]
    #[Route('/api/chats/invite/handle', name: 'api_messenger_chats_invite_handle_accept', methods: ['POST'])]
    public function accept(Request $request): Response
    {
        /** @var AcceptInviteCommand $acceptInviteCommand */
        $acceptInviteCommand = $this->serializerService->deserialize($request->getContent(), AcceptInviteCommand::class);

        $this->commandBus->execute($acceptInviteCommand);

        return new Response();
    }

    #[OA\Delete(summary: 'Отвергнуть приглашение в чат')]
    #[OA\Response(response: 200, description: 'Успешно')]
    #[OA\RequestBody(content: new Model(type: RejectInviteCommand::class))]
    #[OA\Tag(name: 'Chats')]
    #[Security(name: 'Bearer')]
    #[Route('/api/chats/invite/handle', name: 'api_messenger_chats_invite_handle_reject', methods: ['DELETE'])]
    public function reject(Request $request): Response
    {
        /** @var RejectInviteCommand $rejectInviteCommand */
        $rejectInviteCommand = $this->serializerService->deserialize($request->getContent(), RejectInviteCommand::class);

        $this->commandBus->execute($rejectInviteCommand);

        return new Response();
    }
}
