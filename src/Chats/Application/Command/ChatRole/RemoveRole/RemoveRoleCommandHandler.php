<?php

declare(strict_types=1);

namespace App\Chats\Application\Command\ChatRole\RemoveRole;

use App\Chats\Domain\Exception\YouDoesNotOwnerException;
use App\Chats\Domain\Service\ChatRoleRemoveService;
use App\Shared\Application\Command\CommandHandlerInterface;

class RemoveRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ChatRoleRemoveService $chatRoleRemoveService
    ) {
    }

    /**
     * @throws YouDoesNotOwnerException
     */
    public function __invoke(RemoveRoleCommand $command): void
    {
        $this->chatRoleRemoveService->remove($command->profileId, $command->chatId, $command->roleId);
    }
}
