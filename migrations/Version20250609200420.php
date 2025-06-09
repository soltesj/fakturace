<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609200420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document d
            JOIN bank_account ba ON ba.company_id = d.company_id
              AND ba.account_number = SUBSTRING_INDEX(d.bank_account_number, '/', 1)
              AND ba.bank_code = SUBSTRING_INDEX(d.bank_account_number, '/', -1)
            SET d.bank_account_id = ba.id
            WHERE d.bank_account_id IS NULL
              AND d.bank_account_number LIKE '%/%';
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
