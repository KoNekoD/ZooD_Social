<?php

declare(strict_types=1);

namespace App\Chats\Domain\Specification\Chat;

use App\Chats\Domain\Entity\Chat;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\Service\AssertService;
use App\Shared\Domain\Specification\SpecificationInterface;
use Webmozart\Assert\InvalidArgumentException;

class ChatInformationSpecification implements SpecificationInterface
{
    /**
     * @throws ValidationException
     */
    public function satisfy(Chat $chat): void
    {
        try
        {
            AssertService::lengthBetween($chat->getTitle(), 1, 32);
            AssertService::lengthBetween($chat->getDescription(), 1, 512);
        }
        catch (InvalidArgumentException $e)
        {
            throw new ValidationException($e->getMessage());
        }
    }
}
