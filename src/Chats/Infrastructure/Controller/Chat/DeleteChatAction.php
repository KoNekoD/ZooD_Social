<?php

declare(strict_types=1);

namespace App\Chats\Infrastructure\Controller\Chat;

use App\Chats\Application\Command\Chat\DeleteChat\DeleteChatCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Delete(summary: 'Удалить чат')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\RequestBody(content: new Model(type: DeleteChatCommand::class))]
#[OA\Tag(name: 'Chats')]
#[Security(name: 'Bearer')]
#[Route('/api/chats', name: 'api_messenger_chats_delete', methods: ['DELETE'])]
class DeleteChatAction
{
    public function __construct(
        private readonly SerializerServiceInterface $serializerService,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var DeleteChatCommand $deleteChatCommand */
        $deleteChatCommand = $this->serializerService->deserialize($request->getContent(), DeleteChatCommand::class);

        $this->commandBus->execute($deleteChatCommand);

        return new Response();
    }
}
