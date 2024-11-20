<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120112312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT NULL, CHANGE gender gender VARCHAR(20) DEFAULT NULL, CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE address address VARCHAR(100) DEFAULT NULL, CHANGE city city VARCHAR(80) DEFAULT NULL, CHANGE municipality municipality VARCHAR(255) DEFAULT NULL, CHANGE province province VARCHAR(255) DEFAULT NULL, CHANGE latitude latitude NUMERIC(20, 14) DEFAULT NULL, CHANGE longitude longitude NUMERIC(20, 14) DEFAULT NULL');
        $this->addSql('ALTER TABLE device_surplus DROP date');
        $this->addSql('ALTER TABLE device_yield DROP date');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT \'NULL\', CHANGE gender gender VARCHAR(20) DEFAULT \'NULL\', CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATE DEFAULT \'NULL\', CHANGE address address VARCHAR(100) DEFAULT \'NULL\', CHANGE city city VARCHAR(80) DEFAULT \'NULL\', CHANGE municipality municipality VARCHAR(255) DEFAULT \'NULL\', CHANGE province province VARCHAR(255) DEFAULT \'NULL\', CHANGE latitude latitude NUMERIC(20, 14) DEFAULT \'NULL\', CHANGE longitude longitude NUMERIC(20, 14) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE device_surplus ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE device_yield ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
