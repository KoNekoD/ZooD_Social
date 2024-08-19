<?php

declare(strict_types=1);

namespace App\Friends\Domain\Repository;

use App\Friends\Domain\Entity\Friend;
use App\Profiles\Domain\Entity\Profile;

interface FriendRepositoryInterface
{
    public function addFriend(Friend $friend): void;

    public function removeFriend(Friend $friend): void;

    public function save(): void;

    public function findOne(Profile $profile, Profile $friend): Friend;

    /**
     * Ищет друзей по полю с типом Profile.
     *
     * @return Friend[]
     */
    public function findFriend(Profile $profile, int $relationType, int $page): iterable;

    public function isExist(Profile $profile, Profile $friend): bool;
}
