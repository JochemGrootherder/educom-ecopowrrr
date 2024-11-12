<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112105916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT NULL, CHANGE gender gender VARCHAR(20) DEFAULT NULL, CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE address address VARCHAR(100) DEFAULT NULL, CHANGE city city VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT \'NULL\', CHANGE gender gender VARCHAR(20) DEFAULT \'NULL\', CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATE DEFAULT \'NULL\', CHANGE address address VARCHAR(100) DEFAULT \'NULL\', CHANGE city city VARCHAR(80) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
