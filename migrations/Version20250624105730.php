<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250624105730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO payment (             
              company_id,
              type,
              date,
              amount,
              document_id,
              bank_account_id,
              variable_symbol,
              constant_symbol,
              specific_symbol
            )
            SELECT
              tmp.company_id,
              CASE
                WHEN tmp.document_type_id = 5 THEN 'payment_incoming'
                WHEN tmp.document_type_id = 6 THEN 'payment_outcoming'
                WHEN tmp.document_type_id = 7 THEN 'payment_incoming'
                ELSE 'payment_outcoming'
              END AS type,
              tmp.date_issue AS date,
              tmp.price_total AS amount,
              tmp.document_id,
              parent.bank_account_id AS bank_account_id,
              tmp.variable_symbol,
              tmp.constant_symbol,
              tmp.specific_symbol
            FROM document AS tmp
            LEFT JOIN document AS parent ON tmp.document_id = parent.id
            WHERE tmp.document_type_id IN (5, 6, 7, 8);
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
