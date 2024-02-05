<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131014111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company 
    CHANGE name name TEXT NOT NULL,
    CHANGE alias alias TEXT NOT NULL,
    CHANGE contact contact VARCHAR(255) NOT NULL,
    CHANGE street street VARCHAR(128) NOT NULL,
    CHANGE building_number building_number VARCHAR(16) NOT NULL, 
    CHANGE city city VARCHAR(128) NOT NULL, 
    CHANGE zipcode zipcode VARCHAR(6) NOT NULL, 
    CHANGE ic ic VARCHAR(16) NOT NULL, 
    CHANGE dic dic VARCHAR(16) NOT NULL, 
    CHANGE info info VARCHAR(255) NOT NULL, 
    CHANGE phone phone VARCHAR(25) NOT NULL, 
    CHANGE email email VARCHAR(255) NOT NULL, 
    CHANGE web web VARCHAR(255) NOT NULL, 
    CHANGE email_invoice_message email_invoice_message TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE name name TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE alias alias TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE contact contact VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE street street VARCHAR(128) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE building_number building_number VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE city city VARCHAR(128) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE zipcode zipcode VARCHAR(6) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE ic ic VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE dic dic VARCHAR(16) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE info info VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE phone phone VARCHAR(25) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE web web VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE email_invoice_message email_invoice_message TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
    }
}
