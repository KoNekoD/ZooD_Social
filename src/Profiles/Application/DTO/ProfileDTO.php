<?php

declare(strict_types=1);

namespace App\Profiles\Application\DTO;

use App\Profiles\Domain\Entity\Profile;

class ProfileDTO
{
    public string $id;
    public string $firstName;
    public ?string $lastName;

    public static function fromEntity(Profile $profile): self
    {
        $self = new self();

        $self->id = $profile->getId();
        $self->firstName = $profile->getFirstName();
        $self->lastName = $profile->getLastName();

        return $self;
    }
}
