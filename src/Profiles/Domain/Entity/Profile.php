<?php

declare(strict_types=1);

namespace App\Profiles\Domain\Entity;

use App\Shared\Domain\Service\UlidService;
use App\Users\Domain\Entity\User;

class Profile
{
    public const PER_PAGE_LIMIT = 15;

    private readonly string $id;
    private User $user;
    private string $firstName;
    private ?string $lastName;

    public function __construct(User $user, string $firstName, ?string $lastName)
    {
        $this->id = UlidService::generate();
        $this->user = $user;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function updateProfileInformation(string $firstName, ?string $lastName = ''): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
