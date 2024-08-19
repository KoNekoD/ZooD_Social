<?php

declare(strict_types=1);

namespace App\Profiles\Infrastructure\Controller;

use App\Profiles\Application\DTO\ProfileDTO;
use App\Profiles\Application\Query\GetProfiles\GetProfilesQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Service\SerializerServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить список своих профилей')]
#[OA\Response(response: 200, description: 'Успешно', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: ProfileDTO::class))))]
#[OA\Parameter(name: 'page', in: 'query', example: 1)]
#[OA\Tag(name: 'Profiles')]
#[Security(name: 'Bearer')]
#[Route('/api/profiles', name: 'api_profiles_list', methods: ['GET'])]
class GetProfilesAction
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly SerializerServiceInterface $serializerService
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        /** @var ProfileDTO[] $profileDTOs */
        $profileDTOs = $this->queryBus->execute(new GetProfilesQuery($page));

        // #TODO #TODO Там приходит массив дтошек, если выходит ошибка то сделать через цикл
        // Если ок то переписать везде на такую же короткую версию(без цикла)
        $result = $this->serializerService->serialize($profileDTOs);

        return new Response($result);
    }
}
