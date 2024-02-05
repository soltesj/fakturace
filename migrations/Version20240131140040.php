<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131140040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tmp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tmp (IDdocument INT AUTO_INCREMENT NOT NULL, parentID INT NOT NULL, companyID INT NOT NULL, documentTypeID TINYINT(1) NOT NULL, userID INT NOT NULL, customerID INT DEFAULT NULL, paymentTypeID INT NOT NULL, state_id TINYINT(1) DEFAULT NULL, transferTax TINYINT(1) NOT NULL, send TINYINT(1) DEFAULT NULL, documentNumber VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, variableSymbol VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, bankAccountID INT DEFAULT NULL, bankAccount VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, dateIssue DATE NOT NULL, dateTaxable DATE NOT NULL, datePay DATE NOT NULL, customerName VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerContact VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerStreet VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerHouseNumber VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerTown VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerZipcode VARCHAR(6) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerIc VARCHAR(15) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, customerDic VARCHAR(12) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, vatHigh TINYINT(1) NOT NULL, vatLow TINYINT(1) NOT NULL, priceHighVat DOUBLE PRECISION NOT NULL, priceLowVat DOUBLE PRECISION NOT NULL, priceNoVat DOUBLE PRECISION NOT NULL, priceTotal DOUBLE PRECISION NOT NULL, PRIMARY KEY(IDdocument)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
    }
}
