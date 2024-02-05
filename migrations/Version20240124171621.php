<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124171621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE countryID country_id INT NOT NULL, CHANGE VATpayer cat_payer TINYINT(1) NOT NULL, CHANGE buildingNumber building_number VARCHAR(16) NOT NULL,CHANGE emailInvoiceMessage email_invoice_message TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE country_id countryID INT NOT NULL, CHANGE cat_payer VATpayer TINYINT(1) NOT NULL, CHANGE building_number buildingNumber VARCHAR(16) NOT NULL, CHANGE email_invoice_message emailInvoiceMessage TEXT NOT NULL');
    }
}
