<?php

declare(strict_types=1);

namespace App\Chats\Application\DTO;

use App\Chats\Domain\Entity\ChatRole;

class RoleDTO
{
    public int $id;
    public string $name;
    public string $style;
    public bool $creator;
    public bool $canRestrict;
    public bool $default;

    public function __construct(int $id, string $name, string $style, bool $creator, bool $canRestrict, bool $default)
    {
        $this->id = $id;
        $this->name = $name;
        $this->style = $style;
        $this->creator = $creator;
        $this->canRestrict = $canRestrict;
        $this->default = $default;
    }

    public static function fromEntity(ChatRole $role): self
    {
        return new self(
            id: $role->getId(),
            name: $role->getName(),
            style: $role->getStyle(),
            creator: $role->isCreator(),
            canRestrict: $role->isCanRestrict(),
            default: $role->isDefault(),
        );
    }
}
