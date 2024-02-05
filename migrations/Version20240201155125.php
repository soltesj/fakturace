<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201155125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer 
    CHANGE contact contact VARCHAR(255) DEFAULT NULL,
    CHANGE house_number house_number VARCHAR(25) DEFAULT NULL,
    CHANGE town town VARCHAR(255) DEFAULT NULL,
    CHANGE zipcode zipcode VARCHAR(10) DEFAULT NULL,
    CHANGE ic ic VARCHAR(20) DEFAULT NULL,
    CHANGE dic dic VARCHAR(20) DEFAULT NULL,
    CHANGE phone phone VARCHAR(25) DEFAULT NULL,
    CHANGE email email VARCHAR(255) DEFAULT NULL,
    CHANGE web web VARCHAR(255) DEFAULT NULL,
    CHANGE bank_account bank_account VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE contact contact VARCHAR(255) NOT NULL, CHANGE house_number house_number VARCHAR(25) NOT NULL, CHANGE town town VARCHAR(255) NOT NULL, CHANGE zipcode zipcode VARCHAR(10) NOT NULL, CHANGE ic ic VARCHAR(20) NOT NULL, CHANGE dic dic VARCHAR(20) NOT NULL, CHANGE phone phone VARCHAR(25) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE web web VARCHAR(255) NOT NULL, CHANGE bank_account bank_account VARCHAR(40) NOT NULL');
    }
}
