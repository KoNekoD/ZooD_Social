<?php

declare(strict_types=1);

namespace App\Friends\Domain\Entity;

use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;
use App\Friends\Domain\Specification\FriendSpecification;
use App\Profiles\Domain\Entity\Profile;

class Friend
{
    // По каким то причинам при получении данных из фикстуры
    // доктрины в тестах тут появляется (string)ProfileId
    // вместо (Profile)Profile.
    // Пиздец блять...
    /** @var string|Profile */
    private mixed $profile;
    /** @var string|Profile */
    private mixed $friend;

    private int $relationType;

    public const PER_PAGE_LIMIT = 100;

    public const RELATIONS_TARGET = -1; // F -> F

    public const RELATION_MUTUAL = 0; // F <=> F

    public const RELATION_FOLLOWER = 1; // F <- F

    public readonly FriendSpecification $friendSpecification;

    /**
     * @throws UsingSharedUserException
     * @throws SelfFriendAddingException
     */
    public function __construct(Profile $profile, Profile $friend, int $relationType, FriendSpecification $friendSpecification)
    {
        $this->profile = $profile;
        $this->friend = $friend;
        $this->relationType = $relationType;

        $this->friendSpecification = $friendSpecification;
        $this->friendSpecification->satisfy($this);
    }

    public function setRelationType(int $relationType): void
    {
        $this->relationType = $relationType;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getFriend(): Profile
    {
        return $this->friend;
    }

    public function getRelationType(): int
    {
        return $this->relationType;
    }
}
