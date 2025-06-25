<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625012967 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO email_template (company_id, subject, content)
            SELECT c.id, d.subject, d.content
            FROM company c
            JOIN default_email_template d ON c.country_id = d.country_id
            SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
