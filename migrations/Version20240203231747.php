<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203231747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_item CHANGE unit unit VARCHAR(10) DEFAULT NULL, CHANGE price price NUMERIC(10, 2) NOT NULL, CHANGE vat vat SMALLINT DEFAULT NULL, CHANGE price_with_vat price_with_vat TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_item CHANGE unit unit VARCHAR(10) NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE vat vat SMALLINT NOT NULL, CHANGE price_with_vat price_with_vat TINYINT(1) NOT NULL');
    }
}
