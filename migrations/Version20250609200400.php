<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609200400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document 
            SET bank_account_number = REPLACE(bank_account_number, ' ', '')
            WHERE bank_account_number LIKE '% %';
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
