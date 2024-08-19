<?php

declare(strict_types=1);

namespace App\Chats\Domain\Specification;

use App\Chats\Domain\Entity\ChatRole;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\Service\AssertService;
use App\Shared\Domain\Specification\SpecificationInterface;
use Webmozart\Assert\InvalidArgumentException;

class ChatRoleNameSpecification implements SpecificationInterface
{
    /**
     * @throws ValidationException
     */
    public function satisfy(ChatRole $chatRole): void
    {
        try
        {
            AssertService::lengthBetween($chatRole->getName(), 1, 32);
        }
        catch (InvalidArgumentException $e)
        {
            throw new ValidationException($e->getMessage());
        }
    }
}
