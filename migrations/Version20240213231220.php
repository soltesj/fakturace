<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213231220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vat_level ADD country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vat_level ADD CONSTRAINT FK_C0C67BC0F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_C0C67BC0F92F3E70 ON vat_level (country_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vat_level DROP FOREIGN KEY FK_C0C67BC0F92F3E70');
        $this->addSql('DROP INDEX IDX_C0C67BC0F92F3E70 ON vat_level');
        $this->addSql('ALTER TABLE vat_level DROP country_id');
    }
}
