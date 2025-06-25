<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250624105740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DELETE FROM document_price WHERE document_id in (SELECT id FROM document WHERE document_type_id IN (5, 6, 7, 8))
        SQL
        );
        $this->addSql(<<<'SQL'
            DELETE FROM document_item WHERE document_id in (SELECT id FROM document WHERE document_type_id IN (5, 6, 7, 8))
        SQL
        );
        $this->addSql(<<<'SQL'
            DELETE from document WHERE document_type_id IN (5, 6, 7, 8);
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
