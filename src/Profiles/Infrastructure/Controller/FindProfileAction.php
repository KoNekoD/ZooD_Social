<?php

declare(strict_types=1);

namespace App\Profiles\Infrastructure\Controller;

use App\Profiles\Application\DTO\ProfileDTO;
use App\Profiles\Application\Query\FindProfile\FindProfileByIdQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить информацию о профиле')]
#[OA\Response(response: 200, description: 'Успешно', content: new Model(type: ProfileDTO::class))]
#[OA\RequestBody(content: new Model(type: FindProfileByIdQuery::class))]
#[OA\Tag(name: 'Profiles')]
#[Security(name: 'Bearer')]
#[Route('/api/profiles/{profileId}', name: 'api_profiles_profile', methods: ['GET'])]
class FindProfileAction
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly SerializerServiceInterface $serializerService,
    ) {
    }

    public function __invoke(string $profileId): Response
    {
        /** @var ProfileDTO $profileDTO */
        $profileDTO = $this->queryBus->execute(new FindProfileByIdQuery($profileId));

        // Json encoded DTO
        $result = $this->serializerService->serialize($profileDTO);

        return new Response($result);
    }
}
