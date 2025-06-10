<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250610202615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document 
            SET customer_ic = REPLACE(customer_ic, ' ', '')
            WHERE customer_ic LIKE '% %';
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
