<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324115200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document_price (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, price_type_id INT DEFAULT NULL, vat_level_id INT DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_A36954ABC33F7837 (document_id), INDEX IDX_A36954ABAE6A44CF (price_type_id), INDEX IDX_A36954AB49229408 (vat_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE document_price_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE document_price ADD CONSTRAINT FK_A36954ABC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document_price ADD CONSTRAINT FK_A36954ABAE6A44CF FOREIGN KEY (price_type_id) REFERENCES document_price_type (id)');
        $this->addSql('ALTER TABLE document_price ADD CONSTRAINT FK_A36954AB49229408 FOREIGN KEY (vat_level_id) REFERENCES vat_level (id)');

        $this->addSql("INSERT INTO document_price_type (id, name) VALUES (1, 'TOTAL_PRICE')");
        $this->addSql("INSERT INTO document_price_type (id, name) VALUES (2, 'PARTIAL_PRICE')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_price DROP FOREIGN KEY FK_A36954ABC33F7837');
        $this->addSql('ALTER TABLE document_price DROP FOREIGN KEY FK_A36954ABAE6A44CF');
        $this->addSql('ALTER TABLE document_price DROP FOREIGN KEY FK_A36954AB49229408');
        $this->addSql('DROP TABLE document_price');
        $this->addSql('DROP TABLE document_price_type');
    }
}
