<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029085630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, price NUMERIC(8, 2) NOT NULL, INDEX IDX_CAC822D99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_period (price_id INT NOT NULL, period_id INT NOT NULL, INDEX IDX_8821B69ED614C7E7 (price_id), INDEX IDX_8821B69EEC8B7ADE (period_id), PRIMARY KEY(price_id, period_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE price_period ADD CONSTRAINT FK_8821B69ED614C7E7 FOREIGN KEY (price_id) REFERENCES price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE price_period ADD CONSTRAINT FK_8821B69EEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT NULL, CHANGE gender gender VARCHAR(20) DEFAULT NULL, CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE address address VARCHAR(100) DEFAULT NULL, CHANGE city city VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D99395C3F3');
        $this->addSql('ALTER TABLE price_period DROP FOREIGN KEY FK_8821B69ED614C7E7');
        $this->addSql('ALTER TABLE price_period DROP FOREIGN KEY FK_8821B69EEC8B7ADE');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE price_period');
        $this->addSql('ALTER TABLE customer CHANGE firstname firstname VARCHAR(50) DEFAULT \'NULL\', CHANGE gender gender VARCHAR(20) DEFAULT \'NULL\', CHANGE phonenumber phonenumber VARCHAR(20) DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATE DEFAULT \'NULL\', CHANGE address address VARCHAR(100) DEFAULT \'NULL\', CHANGE city city VARCHAR(80) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
