<?php

declare(strict_types=1);

namespace App\Friends\Application\Query\GetFriends;

use App\Friends\Application\DTO\FriendDTO;
use App\Friends\Domain\Repository\FriendRepositoryInterface;
use App\Profiles\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class FindFriendsByProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly FriendRepositoryInterface $friendRepository,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /** @return array<FriendDTO> */
    public function __invoke(FindFriendsByProfileQuery $query): array
    {
        $senderProfile = $this->profileRepository->findById($query->senderProfileId);
        $friends = $this->friendRepository->findFriend($senderProfile, $query->relationType, $query->page);

        $result = [];

        foreach ($friends as $friend)
        {
            $result[] = FriendDTO::fromEntity($friend);
        }

        return $result;
    }
}
