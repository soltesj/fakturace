<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240131103601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document 
    CHANGE parentID parent_id INT NOT NULL, 
    CHANGE companyID company_id INT NOT NULL, 
    CHANGE documentTypeID document_type_id TINYINT(1) NOT NULL, 
    CHANGE userID user_id INT NOT NULL, 
    CHANGE customerID customer_id INT DEFAULT NULL, 
    CHANGE paymentTypeID payment_type_id INT NOT NULL, 
    CHANGE transactionId transaction_id VARCHAR(32) NOT NULL, 
    CHANGE stateID state_id TINYINT(1) NOT NULL, 
    CHANGE transferTax transfer_tax TINYINT(1) NOT NULL, 
    CHANGE documentNumber document_number VARCHAR(20) NOT NULL, 
    CHANGE variableSymbol variable_symbol VARCHAR(10) NOT NULL, 
    CHANGE constantSymbol constant_symbol VARCHAR(4) NOT NULL, 
    CHANGE specificSymbol specific_symbol VARCHAR(10) NOT NULL, 
    CHANGE bankAccountID bank_account_id INT DEFAULT NULL, 
    CHANGE bankAccount bank_account VARCHAR(50) DEFAULT NULL, 
    CHANGE dateIssue date_issue DATE NOT NULL, 
    CHANGE dateTaxable date_taxable DATE NOT NULL, 
    CHANGE datePay date_pay DATE NOT NULL, 
    CHANGE customerName customer_name VARCHAR(255) DEFAULT NULL, 
    CHANGE customerContact customer_contact VARCHAR(255) DEFAULT NULL, 
    CHANGE customerStreet customer_street VARCHAR(255) DEFAULT NULL, 
    CHANGE customerHouseNumber customer_house_number VARCHAR(20) DEFAULT NULL, 
    CHANGE customerTown customer_town VARCHAR(255) DEFAULT NULL, 
    CHANGE customerZipcode customer_zipcode VARCHAR(6) DEFAULT NULL, 
    CHANGE customerIc customer_ic VARCHAR(20) DEFAULT NULL, 
    CHANGE customerDic customer_dic VARCHAR(20) NOT NULL, 
    CHANGE vatHigh vat_high TINYINT(1) NOT NULL, 
    CHANGE vatLow vat_low TINYINT(1) NOT NULL, 
    CHANGE priceWithoutHighVat price_without_high_vat DOUBLE PRECISION NOT NULL, 
    CHANGE priceWithoutLowVat price_without_low_vat DOUBLE PRECISION NOT NULL, 
    CHANGE priceNoVat price_no_vat DOUBLE PRECISION NOT NULL, 
    CHANGE currency currency VARCHAR(4) NOT NULL, 
    CHANGE bank_routing bank_routing VARCHAR(32) DEFAULT NULL, 
    CHANGE iban iban VARCHAR(64) NOT NULL, 
    CHANGE bic bic VARCHAR(64) NOT NULL, 
    CHANGE description description TEXT NOT NULL, 
    CHANGE note note TEXT NOT NULL, 
    CHANGE tag tag TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document
    CHANGE parent_id parentID INT NOT NULL, 
    CHANGE company_id companyID INT NOT NULL, 
    CHANGE document_type_id documentTypeID TINYINT(1) NOT NULL, 
    CHANGE user_id userID INT NOT NULL, 
    CHANGE customer_id customerID INT DEFAULT NULL, 
    CHANGE payment_type_id paymentTypeID INT NOT NULL, 
    CHANGE transaction_id transactionId VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE state_id stateID TINYINT(1) NOT NULL, 
    CHANGE transfer_tax transferTax TINYINT(1) NOT NULL, 
    CHANGE document_number documentNumber VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE variable_symbol variableSymbol VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE constant_symbol constantSymbol VARCHAR(4) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE specific_symbol specificSymbol VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE bank_account_id bankAccountID INT DEFAULT NULL, 
    CHANGE bank_account bankAccount VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE date_issue dateIssue DATE NOT NULL, 
    CHANGE date_taxable dateTaxable DATE NOT NULL, 
    CHANGE date_pay datePay DATE NOT NULL, 
    CHANGE customer_name customerName VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_contact customerContact VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_street customerStreet VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_house_number customerHouseNumber VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_town customerTown VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_zipcode customerZipcode VARCHAR(6) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_ic customerIc VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, 
    CHANGE customer_dic customerDic VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE vat_high vatHigh TINYINT(1) NOT NULL, 
    CHANGE vat_low vatLow TINYINT(1) NOT NULL, 
    CHANGE price_without_high_vat priceWithoutHighVat DOUBLE PRECISION NOT NULL, 
    CHANGE price_without_low_vat priceWithoutLowVat DOUBLE PRECISION NOT NULL, 
    CHANGE price_no_vat priceNoVat DOUBLE PRECISION NOT NULL, CHANGE currency currency VARCHAR(4) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE bank_routing bank_routing VARCHAR(32) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE iban iban VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE bic bic VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE description description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE note note TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE tag tag TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
    }
}
