<?php

declare(strict_types=1);

namespace App\Friends\Domain\Specification;

use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;

class FriendSpecification
{
    public function __construct()
    {
    }

    /**
     * @throws UsingSharedUserException
     * @throws SelfFriendAddingException
     */
    public function satisfy(Friend $friend): void
    {
        if ($friend->getProfile()->getId() === $friend->getFriend()->getId())
        {
            throw new SelfFriendAddingException();
        }

        if ($friend->getProfile()->getUser()->getId() === $friend->getFriend()->getUser()->getId())
        {
            throw new UsingSharedUserException();
        }
    }
}
