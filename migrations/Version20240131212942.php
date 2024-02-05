<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131212942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE alias alias TEXT DEFAULT NULL, CHANGE contact contact VARCHAR(255) DEFAULT NULL, CHANGE info info VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(25) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE web web VARCHAR(255) DEFAULT NULL, CHANGE email_invoice_message email_invoice_message TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE alias alias TEXT NOT NULL, CHANGE contact contact VARCHAR(255) NOT NULL, CHANGE info info VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(25) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE web web VARCHAR(255) NOT NULL, CHANGE email_invoice_message email_invoice_message TEXT NOT NULL');
    }
}
