<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241101145457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, customer_advisor_id INT NOT NULL, zipcode VARCHAR(8) NOT NULL, housenumber INT NOT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(80) NOT NULL, gender VARCHAR(20) DEFAULT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(20) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, bank_details VARCHAR(50) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(80) DEFAULT NULL, INDEX IDX_81398E099B1AA9F5 (customer_advisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_manager (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_7092FC246BF700BD (status_id), UNIQUE INDEX UNIQ_7092FC249395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E099B1AA9F5 FOREIGN KEY (customer_advisor_id) REFERENCES customer_advisor (id)');
        $this->addSql('ALTER TABLE device_manager ADD CONSTRAINT FK_7092FC246BF700BD FOREIGN KEY (status_id) REFERENCES device_status (id)');
        $this->addSql('ALTER TABLE device_manager ADD CONSTRAINT FK_7092FC249395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D99395C3F3');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EFF0D77A5');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FFF0D77A5');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E099B1AA9F5');
        $this->addSql('ALTER TABLE device_manager DROP FOREIGN KEY FK_7092FC246BF700BD');
        $this->addSql('ALTER TABLE device_manager DROP FOREIGN KEY FK_7092FC249395C3F3');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE device_manager');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
