<?php

declare(strict_types=1);

namespace App\Chats\Domain\Specification\Chat;

class ChatSpecification
{
    public function __construct(
        public readonly ChatInformationSpecification $chatInformationSpecification
    ) {
    }
}
