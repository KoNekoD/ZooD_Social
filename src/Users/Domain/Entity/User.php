<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Service\UlidService;
use App\Users\Domain\Service\UserPasswordHasherInterface;

class User implements AuthUserInterface
{
    private string $id;
    private string $login;
    private ?string $password = null;

    public function __construct(string $login)
    {
        $this->id = UlidService::generate();
        $this->login = $login;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(
        ?string $password,
        UserPasswordHasherInterface $passwordHasher
    ): void {
        if (is_null($password))
        {
            $this->password = null;
        }
        else
        {
            $this->password = $passwordHasher->hash($this, $password);
        }
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    public function eraseCredentials(): void
    {
        // TODO.php: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }
}
