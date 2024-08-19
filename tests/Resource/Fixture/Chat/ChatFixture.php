<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Chat;

use App\Chats\Domain\Entity\ChatParticipant;
use App\Chats\Domain\Exception\ParticipantAlreadyExistException;
use App\Chats\Domain\Factory\ChatFactory;
use App\Chats\Domain\Factory\ChatMessageFactory;
use App\Chats\Domain\Factory\ChatParticipantFactory;
use App\Chats\Domain\Factory\ChatRoleFactory;
use App\Profiles\Domain\Entity\Profile;
use App\Shared\Domain\Exception\ValidationException;
use App\Tests\Resource\Fixture\Profile\ProfileFixture;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChatFixture extends Fixture implements DependentFixtureInterface
{
    use FakerTools;

    public const REFERENCE = 'chat';
    public const REFERENCE_ROLE_MEMBER = 'chat_role_member';

    public function __construct(
        private readonly ChatFactory $chatFactory,
        private readonly ChatRoleFactory $chatRoleFactory,
        private readonly ChatParticipantFactory $chatParticipantFactory,
        private readonly ChatMessageFactory $chatMessageFactory,
    ) {
    }

    /**
     * @throws ParticipantAlreadyExistException
     * @throws ValidationException
     */
    public function load(ObjectManager $manager): void
    {
        // Creating chat
        $chat = $this->chatFactory->create(
            $this->getFaker()->title(),
            ' '
        );
        $manager->persist($chat);
        $manager->flush();

        // Creating basic chat roles
        $ownerRole = $this->chatRoleFactory->create($chat, 'Владелец', 'color:orange;', true, true, false);
        $memberRole = $this->chatRoleFactory->create($chat, 'Участник', '', false, false, true);
        $manager->persist($ownerRole);
        $manager->persist($memberRole);
        $manager->flush();

        // Adding creatorProfile in chat with owner role
        /** @var Profile $profileEntity */
        $profileEntity = $this->getReference(ProfileFixture::REFERENCE);
        $creator = $this->chatParticipantFactory->create($profileEntity, $chat, ChatParticipant::STATUS_MEMBER, $ownerRole);
        $manager->persist($creator);
        $manager->flush();

        // Sending test messages
        $message1 = $this->chatMessageFactory->create($profileEntity, $chat, 'TEST MESSAGE 1');
        $message2 = $this->chatMessageFactory->create($profileEntity, $chat, 'TEST MESSAGE 2');
        $manager->persist($message1);
        $manager->persist($message2);
        $manager->flush();

        $this->addReference(self::REFERENCE, $chat);
        $this->addReference(self::REFERENCE_ROLE_MEMBER, $memberRole);
    }

    public function getDependencies(): array
    {
        return [
            ProfileFixture::class,
        ];
    }
}
