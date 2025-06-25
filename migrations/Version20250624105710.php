<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250624105710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document_type set name = 'Faktura zálohová' where id=3
        SQL
        );
        $this->addSql(<<<'SQL'
            UPDATE document_type set name = 'Proforma faktura' where id=4
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document_type set name = 'Faktura zálohová přijatá' where id=3
        SQL
        );
        $this->addSql(<<<'SQL'
            UPDATE document_type set name = 'Faktura zálohová vydaná' where id=4
        SQL
        );
    }
}
