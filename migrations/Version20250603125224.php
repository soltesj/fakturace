<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250603125224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document SET vat_mode = 'domestic_reverse_charge' WHERE transfer_tax = 1
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
