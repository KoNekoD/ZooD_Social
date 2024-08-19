<?php

declare(strict_types=1);

namespace App\Chats\Application\DTO;

use App\Chats\Domain\Entity\Chat;

class ChatDTO
{
    public string $id;
    public string $title;
    public string $description;
    /** @var RoleDTO[] */
    public array $roles;

    public static function fromEntity(Chat $chat): self
    {
        $self = new self();

        $self->id = $chat->getId();
        $self->title = $chat->getTitle();
        $self->description = $chat->getDescription();

        foreach ($chat->getRoles() as $role)
        {
            $self->roles[] = RoleDTO::fromEntity($role);
        }

        return $self;
    }
}
