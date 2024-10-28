<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028152134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT NULL, CHANGE gender gender VARCHAR(20) DEFAULT NULL, CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE device_manager ADD device_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE device_manager ADD CONSTRAINT FK_7092FC245017142C FOREIGN KEY (device_status_id) REFERENCES device_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7092FC245017142C ON device_manager (device_status_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT \'NULL\', CHANGE gender gender VARCHAR(20) DEFAULT \'NULL\', CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE device_manager DROP FOREIGN KEY FK_7092FC245017142C');
        $this->addSql('DROP INDEX UNIQ_7092FC245017142C ON device_manager');
        $this->addSql('ALTER TABLE device_manager DROP device_status_id');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
