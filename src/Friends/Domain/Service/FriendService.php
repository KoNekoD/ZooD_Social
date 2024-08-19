<?php

declare(strict_types=1);

namespace App\Friends\Domain\Service;

use App\Friends\Domain\Entity\Friend;
use App\Friends\Domain\Exception\SelfFriendAddingException;
use App\Friends\Domain\Exception\UsingSharedUserException;
use App\Friends\Domain\Factory\FriendFactory;
use App\Friends\Domain\Repository\FriendRepositoryInterface;
use App\Profiles\Domain\Entity\Profile;

class FriendService
{
    public function __construct(
        private readonly FriendRepositoryInterface $friendRepository,
        private readonly FriendFactory $friendFactory,
    ) {
    }

    /**
     * @throws UsingSharedUserException
     * @throws SelfFriendAddingException
     */
    public function addInFriends(Profile $sender, Profile $destination): void
    {
        // Search, if destination added sender if friends
        if ($this->friendRepository->isExist($sender, $destination))
        {
            $friend = $this->friendRepository->findOne($sender, $destination);

            /*
             * Если кто-то добавил меня в друзья
             * Я сделаю эту дружбу взаимной
             *
             * В силу технических особенностей таблицы такая вот проверка(наличие поля "Стрелочка",
             * которое указывает в какую сторону направлен запрос дружбы). Это было сделано в целях оптимизации.
             * -- Вместо 2х записей в БД будет всего лишь одна
             */
            if (
                (
                    (Friend::RELATION_FOLLOWER === $friend->getRelationType())
                    &&
                    // (Me)F <- F
                    // (sender request to add friend)F <- (destination)F
                    ($friend->getProfile()->getId() === $sender->getId()) // | Me | otherProfile | <- |
                )
                || // Or
                (
                    (Friend::RELATIONS_TARGET === $friend->getRelationType())
                    &&
                    // F -> (Me)F
                    // (destination)F -> (sender request to add friend)F
                    $friend->getFriend()->getId() === $sender->getId() // | otherProfile | Me | -> |
                )
            ) {
                $friend->setRelationType(Friend::RELATION_MUTUAL);
                $this->friendRepository->save(); // Флашим данные
            }
        }
        else
        {
            $friend = $this->friendFactory->create(
                $sender,
                $destination,
                Friend::RELATIONS_TARGET // (Me)F -> F
            );

            // Добавляем друга в базу
            $this->friendRepository->addFriend($friend);
        }
    }

    public function removeFromFriends(Profile $sender, Profile $destination): void
    {
        $friend = $this->friendRepository->findOne($sender, $destination);

        // (sender(Me))F -> (destination)F
        // (sender(Me))F <-> (destination)F
        if ($friend->getProfile()->getId() === $sender->getId())// Profile(Me) | Friend | RelationType
        {
            // (Me)F -> F
            if (Friend::RELATIONS_TARGET === $friend->getRelationType())
            {
                $this->friendRepository->removeFriend($friend);
            }

            // (Me)F <-> F
            if (Friend::RELATION_MUTUAL === $friend->getRelationType())
            {
                // F <- F
                $friend->setRelationType(Friend::RELATION_FOLLOWER);
                $this->friendRepository->save();
            }
        }

        // (destination)F <- (sender(Me))F
        // (destination)F <-> (sender(Me))F
        if ($friend->getFriend()->getId() === $sender->getId())// Profile | Friend(Me) | RelationType
        {
            // F <- F
            if (Friend::RELATION_FOLLOWER === $friend->getRelationType())
            {
                // Delete
                $this->friendRepository->removeFriend($friend);
            }

            // F <-> F
            if (Friend::RELATION_MUTUAL === $friend->getRelationType())
            {
                // F -> F
                $friend->setRelationType(Friend::RELATIONS_TARGET);
                $this->friendRepository->save();
            }
        }
    }
}
