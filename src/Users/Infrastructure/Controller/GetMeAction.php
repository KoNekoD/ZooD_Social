<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Domain\Security\UserFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Get(summary: 'Получить информацию о своём пользователе')]
#[OA\Tag(name: 'Users')]
#[Security(name: 'Bearer')]
#[Route('/api/users/me', methods: ['GET'])]
class GetMeAction
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userFetcher->getAuthUser();

        return new JsonResponse(
            [
                'id' => $user->getId(),
                'login' => $user->getLogin(),
            ]
        );
    }
}
