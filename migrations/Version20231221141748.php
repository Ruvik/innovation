<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231221141748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init tables: bonus, client, client_reward.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE bonus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_reward_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bonus (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type integer NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id VARCHAR(255) NOT NULL, email_verified BOOLEAN NOT NULL, is_birthday BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client_reward (id VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, bonus_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_client_bonus ON client_reward (client_id, bonus_id)');

        $this->addSql('ALTER TABLE client_reward ADD CONSTRAINT fk_client_reward_client FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_reward ADD CONSTRAINT fk_client_reward_bonus FOREIGN KEY (bonus_id) REFERENCES bonus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE bonus_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_reward_id_seq CASCADE');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_reward');
    }
}
