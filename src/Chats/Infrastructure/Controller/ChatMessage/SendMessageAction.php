<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\ChatMessage;

use App\Chats\Application\Command\ChatMessage\SendMessage\SendMessageCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Post(summary: 'Отправить сообщение')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\RequestBody(content: new Model(type: SendMessageCommand::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats/message', name: 'api_chats_message_send', methods: ['POST'])]
class SendMessageAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var SendMessageCommand $sendMessageCommand */
        $sendMessageCommand = $this->serializerService->deserialize($request->getContent(), SendMessageCommand::class);

        $this->commandBus->execute($sendMessageCommand);

        return new Response();
    }
}
