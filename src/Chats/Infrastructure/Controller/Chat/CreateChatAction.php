<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\Chat;

use App\Chats\Application\Command\Chat\CreateChat\CreateChatCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Post(summary: 'Создать чат')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\RequestBody(content: new Model(type: CreateChatCommand::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats', name: 'api_messenger_chats_create', methods: ['POST'])]
class CreateChatAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var CreateChatCommand $createChatCommand */
        $createChatCommand = $this->serializerService->deserialize($request->getContent(), CreateChatCommand::class);

        $this->commandBus->execute($createChatCommand);

        return new Response();
    }
}
