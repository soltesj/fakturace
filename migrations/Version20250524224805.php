<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524224805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX date_issue_idx ON document
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_document_company_date ON document (company_id, date_issue)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX idx_document_company_date ON document
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX date_issue_idx ON document (date_issue)
        SQL
        );
    }
}
