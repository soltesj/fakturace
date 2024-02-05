<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131124255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fio_transaction_upload 
    CHANGE companyID company_id INT NOT NULL, 
    CHANGE customerName customer_name VARCHAR(128) NOT NULL, 
    CHANGE bankAccountID bank_account_id INT NOT NULL, 
    CHANGE accountTo account_to VARCHAR(32) NOT NULL, 
    CHANGE bankCode bank_code VARCHAR(32) NOT NULL, 
    CHANGE variableSymbol variable_symbol VARCHAR(16) NOT NULL, 
    CHANGE constantSymbol constant_symbol VARCHAR(16) NOT NULL, 
    CHANGE currency currency VARCHAR(3) NOT NULL, 
    CHANGE specificSymbol specificSymbol VARCHAR(16) NOT NULL, CHANGE message message VARCHAR(140) NOT NULL, CHANGE paymentType paymentType VARCHAR(8) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fio_transaction_upload
    CHANGE company_id companyID INT NOT NULL,
    CHANGE customer_name customerName VARCHAR(128) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE bank_account_id bankAccountID INT NOT NULL,
    CHANGE account_to accountTo VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE bank_code bankCode VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE variable_symbol variableSymbol VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE constant_symbol constantSymbol VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE currency currency VARCHAR(3) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`,
    CHANGE specificSymbol specificSymbol VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, CHANGE message message VARCHAR(140) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, CHANGE paymentType paymentType VARCHAR(8) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`');
    }
}
