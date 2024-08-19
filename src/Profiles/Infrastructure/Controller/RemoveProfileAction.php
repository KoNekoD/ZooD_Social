<?php

declare(strict_types=1);

namespace App\Profiles\Infrastructure\Controller;

use App\Profiles\Application\Command\RemoveProfile\RemoveProfileCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Delete(summary: 'Удалить профиль')]
#[OA\Response(response: 200, description: 'Успешно')]
#[OA\Tag(name: 'Profiles')]
#[Security(name: 'Bearer')]
#[Route('/api/profiles/{profileId}', name: 'api_profiles_delete', methods: ['DELETE'])]
class RemoveProfileAction
{
    public function __construct(public readonly CommandBusInterface $commandBus)
    {
    }

    public function __invoke(string $profileId): Response
    {
        $this->commandBus->execute(new RemoveProfileCommand($profileId));

        return new Response();
    }
}
