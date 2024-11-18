<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118131447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, customer_advisor_id INT NOT NULL, zipcode VARCHAR(8) NOT NULL, housenumber INT NOT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(80) NOT NULL, gender VARCHAR(20) DEFAULT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(20) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, bank_details VARCHAR(50) NOT NULL, address VARCHAR(100) DEFAULT NULL, city VARCHAR(80) DEFAULT NULL, INDEX IDX_81398E099B1AA9F5 (customer_advisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_advisor (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(120) NOT NULL, password VARCHAR(120) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, device_manager_id INT NOT NULL, device_type_id INT NOT NULL, device_status_id INT NOT NULL, serial_number VARCHAR(80) NOT NULL, INDEX IDX_92FB68EFF0D77A5 (device_manager_id), INDEX IDX_92FB68E4FFA550E (device_type_id), INDEX IDX_92FB68E5017142C (device_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_manager (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_7092FC246BF700BD (status_id), UNIQUE INDEX UNIQ_7092FC249395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_surplus (id INT AUTO_INCREMENT NOT NULL, device_manager_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, INDEX IDX_A311DC65FF0D77A5 (device_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_yield (id INT AUTO_INCREMENT NOT NULL, device_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, INDEX IDX_7FC0A56C94A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, device_manager_id INT NOT NULL, date DATE NOT NULL, message MEDIUMTEXT NOT NULL, INDEX IDX_B6BD307FFF0D77A5 (device_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, price NUMERIC(8, 2) NOT NULL, date DATE NOT NULL, INDEX IDX_CAC822D99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E099B1AA9F5 FOREIGN KEY (customer_advisor_id) REFERENCES customer_advisor (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68EFF0D77A5 FOREIGN KEY (device_manager_id) REFERENCES device_manager (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E4FFA550E FOREIGN KEY (device_type_id) REFERENCES device_type (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E5017142C FOREIGN KEY (device_status_id) REFERENCES device_status (id)');
        $this->addSql('ALTER TABLE device_manager ADD CONSTRAINT FK_7092FC246BF700BD FOREIGN KEY (status_id) REFERENCES device_status (id)');
        $this->addSql('ALTER TABLE device_manager ADD CONSTRAINT FK_7092FC249395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE device_surplus ADD CONSTRAINT FK_A311DC65FF0D77A5 FOREIGN KEY (device_manager_id) REFERENCES device_manager (id)');
        $this->addSql('ALTER TABLE device_yield ADD CONSTRAINT FK_7FC0A56C94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FFF0D77A5 FOREIGN KEY (device_manager_id) REFERENCES device_manager (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E099B1AA9F5');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68EFF0D77A5');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E4FFA550E');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E5017142C');
        $this->addSql('ALTER TABLE device_manager DROP FOREIGN KEY FK_7092FC246BF700BD');
        $this->addSql('ALTER TABLE device_manager DROP FOREIGN KEY FK_7092FC249395C3F3');
        $this->addSql('ALTER TABLE device_surplus DROP FOREIGN KEY FK_A311DC65FF0D77A5');
        $this->addSql('ALTER TABLE device_yield DROP FOREIGN KEY FK_7FC0A56C94A4C7D4');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FFF0D77A5');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D99395C3F3');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_advisor');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE device_manager');
        $this->addSql('DROP TABLE device_status');
        $this->addSql('DROP TABLE device_surplus');
        $this->addSql('DROP TABLE device_type');
        $this->addSql('DROP TABLE device_yield');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
