<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215173555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document CHANGE iban iban VARCHAR(64) DEFAULT NULL, CHANGE bic bic VARCHAR(64) DEFAULT NULL, CHANGE customer_dic customer_dic VARCHAR(20) DEFAULT NULL, CHANGE vat_high vat_high TINYINT(1) DEFAULT NULL, CHANGE vat_low vat_low TINYINT(1) DEFAULT NULL, CHANGE tag tag TEXT DEFAULT NULL, CHANGE priceTotal price_total NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document CHANGE iban iban VARCHAR(64) NOT NULL, CHANGE bic bic VARCHAR(64) NOT NULL, CHANGE customer_dic customer_dic VARCHAR(20) NOT NULL, CHANGE vat_high vat_high TINYINT(1) NOT NULL, CHANGE vat_low vat_low TINYINT(1) NOT NULL, CHANGE tag tag TEXT NOT NULL, CHANGE price_total priceTotal NUMERIC(10, 2) DEFAULT NULL');
    }
}
