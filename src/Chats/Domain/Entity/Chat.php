<?php

declare(strict_types=1);

namespace App\Chats\Domain\Entity;

use App\Chats\Domain\Specification\Chat\ChatSpecification;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\Service\UlidService;

class Chat
{
    public const PER_PAGE_LIMIT = 100;

    private readonly string $id;
    private string $title;
    private string $description;

    /** @var iterable<ChatRole>|null */
    private mixed $roles;

    private readonly ChatSpecification $chatSpecification;

    /**
     * @throws ValidationException
     */
    public function __construct(
        string $title,
        string $description,
        ChatSpecification $chatSpecification,
    ) {
        $this->id = UlidService::generate();
        $this->title = $title;
        $this->description = $description;
        $this->roles = []; // Doctrine automatically sets

        $this->chatSpecification = $chatSpecification;
        $this->chatSpecification->chatInformationSpecification->satisfy($this);
    }

    /**
     * @throws ValidationException
     */
    public function updateChatInformation(
        string $title,
        string $description
    ): void {
        $this->title = $title;
        $this->description = $description;

        $this->chatSpecification->chatInformationSpecification->satisfy($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return iterable<ChatRole> */
    public function getRoles(): iterable
    {
        return $this->roles;
    }
}
