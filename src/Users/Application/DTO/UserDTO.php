<?php

declare(strict_types=1);

namespace App\Users\Application\DTO;

use App\Users\Domain\Entity\User;

class UserDTO
{
    public function __construct(public readonly string $id, public readonly string $login)
    {
    }

    public static function fromEntity(User $user): self
    {
        return new self($user->getId(), $user->getLogin());
    }
}
