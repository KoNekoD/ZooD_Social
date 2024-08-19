<?php

declare(strict_types=1);

namespace App\Chats\Domain\Entity;

use App\Chats\Domain\Specification\ChatRoleNameSpecification;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\Specification\SpecificationInterface;

class ChatRole
{
    private int $id;
    private Chat $chat;
    private string $name;
    private string $style;
    /** @var bool Can:
     * 1. Add/Remove Roles
     * 2. Appoint role to participant
     */
    private bool $creator;
    /** @var bool Can:
     * 1. Kick
     * 2. TODO.php mute
     * 3. TODO.php delete messages
     */
    private bool $canRestrict;
    private bool $default;

    /** @var ChatRoleNameSpecification */
    private SpecificationInterface $chatRoleNameSpecification;

    /**
     * @throws ValidationException
     */
    public function __construct(
        Chat $chat,
        string $name,
        string $style,
        bool $creator,
        bool $canRestrict,
        bool $default,
        ChatRoleNameSpecification $chatRoleNameSpecification,
    ) {
        $this->id = -1; // Doctrine automatically sets variable
        $this->chat = $chat;
        $this->name = $name;
        $this->style = $style;
        $this->creator = $creator;
        $this->canRestrict = $canRestrict;
        $this->default = $default;

        $this->chatRoleNameSpecification = $chatRoleNameSpecification;
        $this->chatRoleNameSpecification->satisfy($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function isCreator(): bool
    {
        return $this->creator;
    }

    public function isCanRestrict(): bool
    {
        return $this->canRestrict;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @throws ValidationException
     */
    public function updateRoleInformation(
        string $name,
        string $style,
        bool $canRestrict,
    ): void {
        $this->name = $name;
        $this->style = $style; // TODO.php возможно стили тоже стоит валидировать
        $this->canRestrict = $canRestrict;

        $this->chatRoleNameSpecification->satisfy($this);
    }
}
