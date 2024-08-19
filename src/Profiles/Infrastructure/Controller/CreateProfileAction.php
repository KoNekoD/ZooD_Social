<?php

declare(strict_types=1);

namespace App\Profiles\Infrastructure\Controller;

use App\Profiles\Application\Command\CreateProfile\CreateProfileCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Post(summary: 'Создать новый профиль')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\RequestBody(content: new Model(type: CreateProfileCommand::class))]
#[OA\Tag(name: 'Profiles')]
#[Security(name: 'Bearer')]
#[Route('/api/profiles', name: 'api_profiles_create', methods: ['POST'])]
class CreateProfileAction
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly SerializerServiceInterface $serializerService,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var CreateProfileCommand $addProfileCommand */
        $addProfileCommand = $this->serializerService->deserialize($request->getContent(), CreateProfileCommand::class);

        $this->commandBus->execute($addProfileCommand);

        return new Response();
    }
}
