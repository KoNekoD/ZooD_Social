<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221029145202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chats_chat (id VARCHAR(26) NOT NULL, title VARCHAR(32) NOT NULL, description VARCHAR(512) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chats_message (id VARCHAR(255) NOT NULL, from_id VARCHAR(26) NOT NULL, chat_id VARCHAR(26) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id, from_id, chat_id))');
        $this->addSql('CREATE INDEX IDX_9212778578CED90B ON chats_message (from_id)');
        $this->addSql('CREATE INDEX IDX_921277851A9A7125 ON chats_message (chat_id)');
        $this->addSql('CREATE TABLE chats_participant (chat_id VARCHAR(26) NOT NULL, profile_id VARCHAR(26) NOT NULL, role_id INT DEFAULT NULL, status INT NOT NULL, PRIMARY KEY(chat_id, profile_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E0D4260D60322AC ON chats_participant (role_id)');
        $this->addSql('CREATE INDEX IDX_5E0D42601A9A7125 ON chats_participant (chat_id)');
        $this->addSql('CREATE INDEX IDX_5E0D4260CCFA12B8 ON chats_participant (profile_id)');
        $this->addSql('CREATE TABLE chats_role (id SERIAL NOT NULL, chat_id VARCHAR(26) DEFAULT NULL, name VARCHAR(32) NOT NULL, style TEXT NOT NULL, is_creator BOOLEAN NOT NULL, can_restrict BOOLEAN NOT NULL, is_default BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B5FD2A71A9A7125 ON chats_role (chat_id)');
        $this->addSql('CREATE TABLE friends_friend (profile_id VARCHAR(26) NOT NULL, friend_id VARCHAR(26) NOT NULL, relation_type SMALLINT NOT NULL, PRIMARY KEY(profile_id, friend_id))');
        $this->addSql('CREATE INDEX IDX_36BBFEF0CCFA12B8 ON friends_friend (profile_id)');
        $this->addSql('CREATE INDEX IDX_36BBFEF06A5458E8 ON friends_friend (friend_id)');
        $this->addSql('CREATE TABLE profiles_profile (id VARCHAR(26) NOT NULL, user_id VARCHAR(26) DEFAULT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68B05B74A76ED395 ON profiles_profile (user_id)');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE users_user (id VARCHAR(26) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_421A9847AA08CB10 ON users_user (login)');
        $this->addSql('ALTER TABLE chats_message ADD CONSTRAINT FK_9212778578CED90B FOREIGN KEY (from_id) REFERENCES profiles_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats_message ADD CONSTRAINT FK_921277851A9A7125 FOREIGN KEY (chat_id) REFERENCES chats_chat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats_participant ADD CONSTRAINT FK_5E0D4260D60322AC FOREIGN KEY (role_id) REFERENCES chats_role (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats_participant ADD CONSTRAINT FK_5E0D42601A9A7125 FOREIGN KEY (chat_id) REFERENCES chats_chat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats_participant ADD CONSTRAINT FK_5E0D4260CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profiles_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats_role ADD CONSTRAINT FK_7B5FD2A71A9A7125 FOREIGN KEY (chat_id) REFERENCES chats_chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friends_friend ADD CONSTRAINT FK_36BBFEF0CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profiles_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friends_friend ADD CONSTRAINT FK_36BBFEF06A5458E8 FOREIGN KEY (friend_id) REFERENCES profiles_profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profiles_profile ADD CONSTRAINT FK_68B05B74A76ED395 FOREIGN KEY (user_id) REFERENCES users_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('ALTER TABLE chats_message DROP CONSTRAINT FK_9212778578CED90B');
        $this->addSql('ALTER TABLE chats_message DROP CONSTRAINT FK_921277851A9A7125');
        $this->addSql('ALTER TABLE chats_participant DROP CONSTRAINT FK_5E0D4260D60322AC');
        $this->addSql('ALTER TABLE chats_participant DROP CONSTRAINT FK_5E0D42601A9A7125');
        $this->addSql('ALTER TABLE chats_participant DROP CONSTRAINT FK_5E0D4260CCFA12B8');
        $this->addSql('ALTER TABLE chats_role DROP CONSTRAINT FK_7B5FD2A71A9A7125');
        $this->addSql('ALTER TABLE friends_friend DROP CONSTRAINT FK_36BBFEF0CCFA12B8');
        $this->addSql('ALTER TABLE friends_friend DROP CONSTRAINT FK_36BBFEF06A5458E8');
        $this->addSql('ALTER TABLE profiles_profile DROP CONSTRAINT FK_68B05B74A76ED395');
        $this->addSql('DROP TABLE chats_chat');
        $this->addSql('DROP TABLE chats_message');
        $this->addSql('DROP TABLE chats_participant');
        $this->addSql('DROP TABLE chats_role');
        $this->addSql('DROP TABLE friends_friend');
        $this->addSql('DROP TABLE profiles_profile');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE users_user');
    }
}
