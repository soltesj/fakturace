<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131134527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE numbers 
    CHANGE documentTypeID document_type_id VARCHAR(3) DEFAULT NULL, 
    CHANGE numberFormat number_format VARCHAR(15) DEFAULT NULL, 
    CHANGE companyID company_id INT NOT NULL, 
    CHANGE nextNumber next_number INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE numbers
    CHANGE document_type_id documentTypeID VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE number_format numberFormat VARCHAR(15) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE company_id companyID INT NOT NULL, 
    CHANGE next_number nextNumber INT DEFAULT NULL');
    }
}
