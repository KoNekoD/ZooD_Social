<?php

declare(strict_types=1);

namespace App\Friends\Domain\Factory;

use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;
use App\Friends\Domain\Specification\FriendSpecification;
use App\Profiles\Domain\Entity\Profile;

class FriendFactory
{
    public function __construct(private readonly FriendSpecification $friendSpecification)
    {
    }

    /**
     * @throws UsingSharedUserException
     * @throws SelfFriendAddingException
     */
    public function create(Profile $profile, Profile $friend, int $relationType): Friend
    {
        return new Friend($profile, $friend, $relationType, $this->friendSpecification);
    }
}
