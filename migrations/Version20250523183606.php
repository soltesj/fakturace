<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250523183606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_inbox_identifier ON company_inbox_identifier
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE company_inbox_identifier CHANGE inbox_identifier identifier VARCHAR(20) NOT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_inbox_identifier ON company_inbox_identifier (identifier)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_inbox_identifier ON company_inbox_identifier
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE company_inbox_identifier CHANGE identifier inbox_identifier VARCHAR(20) NOT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_inbox_identifier ON company_inbox_identifier (inbox_identifier)
        SQL
        );
    }
}
