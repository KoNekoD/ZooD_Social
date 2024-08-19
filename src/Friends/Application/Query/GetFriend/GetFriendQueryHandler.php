<?php

declare(strict_types=1);

namespace App\Friends\Application\Query\GetFriend;

use App\Friends\Application\DTO\FriendDTO;
use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Repository\FriendRepositoryInterface;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetFriendQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly FriendRepositoryInterface $friendRepository,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function __invoke(GetFriendQuery $query): FriendDTO
    {
        $senderProfile = $this->profileRepository->findById($query->senderProfileId);
        $destinationProfile = $this->profileRepository->findById($query->destinationProfileId);

        $friend = $this->friendRepository->findOne($senderProfile, $destinationProfile);

        // Если поле отправителя не требует изменений:
        // Me | otherProfile
        if ($friend->getProfile()->getId() === $senderProfile->getId())
        {
            return new FriendDTO($senderProfile->getId(), $destinationProfile->getId(), $friend->getRelationType());
        }

        // Иначе: нужно перевернуть для красивого отображения

        // F <- (Me)F
        if (Friend::RELATION_FOLLOWER === $friend->getRelationType())
        {
            // Rotate to (Me)F -> F
            return new FriendDTO($senderProfile->getId(), $destinationProfile->getId(), Friend::RELATIONS_TARGET);
        }

        // F -> (Me)F
        if (Friend::RELATIONS_TARGET === $friend->getRelationType())
        {
            // Rotate to (Me)F <- F
            return new FriendDTO($senderProfile->getId(), $destinationProfile->getId(), Friend::RELATION_FOLLOWER);
        }

        // Были проверены все косвенные типы дружбы(-> и <-), они не подошли, значит он: <->
        return new FriendDTO($senderProfile->getId(), $destinationProfile->getId(), Friend::RELATION_MUTUAL);
    }
}
