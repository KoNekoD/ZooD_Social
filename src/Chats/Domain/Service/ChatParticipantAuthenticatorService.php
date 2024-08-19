<?php

declare(strict_types=1);

namespace App\Chats\Domain\Service;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Repository\ChatParticipantRepositoryInterface;
use App\Chats\Domain\Repository\ChatRepositoryInterface;
use App\Profiles\Domain\Exception\NotYourProfileException;
use App\Profiles\Domain\Service\ProfileAuthenticatorService;

class ChatParticipantAuthenticatorService
{
    public function __construct(
        private readonly ProfileAuthenticatorService $profileAuthenticatorService,
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly ChatParticipantRepositoryInterface $chatParticipantRepository,
    ) {
    }

    /**
     * Проверяет является ли текущий авторизованный профиль участников чата.
     *
     * @throws NotYourProfileException
     */
    public function authenticate(string $profileId, string $chatId): ChatParticipant
    {
        $profile = $this->profileAuthenticatorService->authenticate($profileId);

        $chat = $this->chatRepository->findById($chatId);

        return $this->chatParticipantRepository->findOne($profile, $chat);
    }
}
