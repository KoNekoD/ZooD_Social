<?php

declare(strict_types=1);

namespace App\Chats\Application\Query\Chat\GetChats;

use App\Chats\Application\DTO\ChatDTO;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetChatsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * @return array<ChatDTO>
     *
     * @throws NotYourProfileException
     */
    public function __invoke(GetChatsQuery $command): array
    {
        $profile = $this->profileAuthenticatorService->authenticate($command->profileId);

        $chatParticipantArray = $this->chatParticipantRepository->findByProfile($profile, $command->page);

        $result = [];

        foreach ($chatParticipantArray as $participant)
        {
            $result[] = ChatDTO::fromEntity($participant->getChat());
        }

        return $result;
    }
}
