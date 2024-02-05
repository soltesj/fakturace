<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131132139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images CHANGE type type VARCHAR(10) NOT NULL, CHANGE url url VARCHAR(255) NOT NULL, CHANGE companyID company_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images CHANGE type type VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, CHANGE company_id companyID INT NOT NULL');
    }
}
