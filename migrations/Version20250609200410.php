<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609200410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO bank_account (
              company_id,
              sequence,
              short_name,
              name,
              account_number,
              bank_code,
              iban,
              bic,
              routing_number,
              status_id
            )
            SELECT 
              tmp.company_id,
              1 AS sequence,
              tmp.bank_code AS short_name, -- zjednodušeně, můžeš nahradit za mapu názvů
              tmp.bank_code AS name,
              tmp.account_number,
              tmp.bank_code,
              tmp.iban,
              tmp.bic,
              tmp.routing_number,
              3 AS status_id
            FROM (
              SELECT DISTINCT
                d.company_id,
                SUBSTRING_INDEX(d.bank_account_number, '/', 1) AS account_number,
                SUBSTRING_INDEX(d.bank_account_number, '/', -1) AS bank_code,
                d.iban,
                d.bic,
                d.bank_routing AS routing_number
              FROM document d
              WHERE d.bank_account_id IS NULL
                AND d.bank_account_number LIKE '%/%'
                AND d.bank_account_number IS NOT NULL
            ) AS tmp
            LEFT JOIN bank_account ba
              ON ba.company_id = tmp.company_id
              AND ba.account_number = tmp.account_number
              AND ba.bank_code = tmp.bank_code
            WHERE ba.id IS NULL;
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
