<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240208151008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document 
    CHANGE rate rate NUMERIC(6, 2) DEFAULT NULL, 
    CHANGE price_without_high_vat price_without_high_vat NUMERIC(10, 2) DEFAULT NULL, 
    CHANGE price_without_low_vat price_without_low_vat NUMERIC(10, 2) DEFAULT NULL, 
    CHANGE price_no_vat price_no_vat NUMERIC(10, 2) DEFAULT NULL, 
    CHANGE priceTotal priceTotal NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document CHANGE rate rate DOUBLE PRECISION NOT NULL, CHANGE price_without_high_vat price_without_high_vat DOUBLE PRECISION NOT NULL, CHANGE price_without_low_vat price_without_low_vat DOUBLE PRECISION NOT NULL, CHANGE price_no_vat price_no_vat DOUBLE PRECISION NOT NULL, CHANGE priceTotal priceTotal DOUBLE PRECISION NOT NULL');
    }
}
