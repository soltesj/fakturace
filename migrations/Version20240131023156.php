<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131023156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer 
    CHANGE companyID company_id INT NOT NULL, 
    CHANGE countryID country_id INT NOT NULL, 
    CHANGE houseNumber house_number VARCHAR(25) NOT NULL, 
    CHANGE bankAccount bank_account VARCHAR(40) NOT NULL, 
    CHANGE name name VARCHAR(255) NOT NULL, 
    CHANGE contact contact VARCHAR(255) NOT NULL, 
    CHANGE street street VARCHAR(255) NOT NULL, 
    CHANGE town town VARCHAR(255) NOT NULL, 
    CHANGE zipcode zipcode VARCHAR(10) NOT NULL, 
    CHANGE ic ic VARCHAR(20) NOT NULL, 
    CHANGE dic dic VARCHAR(20) NOT NULL, 
    CHANGE phone phone VARCHAR(25) NOT NULL, 
    CHANGE email email VARCHAR(255) NOT NULL, 
    CHANGE web web VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer 
    CHANGE company_id companyID INT NOT NULL,
    CHANGE country_id countryID INT NOT NULL,
    CHANGE house_number houseNumber VARCHAR(25) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`,
    CHANGE bank_account bankAccount VARCHAR(40) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`,
    CHANGE name name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE contact contact VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE street street VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE town town VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE zipcode zipcode VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE ic ic VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE dic dic VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE phone phone VARCHAR(25) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE email email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, 
    CHANGE web web VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
    }
}
