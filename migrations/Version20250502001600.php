<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502001600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE country_id country_id INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD vat_mode VARCHAR(255) DEFAULT NULL
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE country_id country_id INT NOT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP vat_mode
        SQL
        );
    }
}
