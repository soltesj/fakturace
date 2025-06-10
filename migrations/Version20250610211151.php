<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250610211151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document d
            JOIN customer c
              ON c.company_id = d.company_id
                 AND (
                   (d.customer_ic IS NOT NULL AND d.customer_ic = c.company_number)
                   OR
                   (d.customer_ic IS NULL AND d.customer_name = c.name)
                 )
            SET d.customer_id = c.id
            WHERE d.customer_id IS NULL;
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
