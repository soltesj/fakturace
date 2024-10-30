<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240628212328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO document_price (document_id, price_type_id, vat_level_id, amount, vat_amount)
            SELECT
                d.id AS document_id,
                2 AS price_type_id,
                9 AS vat_level_id,
                d.price_without_high_vat AS amount,
                d.price_without_high_vat * d.vat_high AS vat_amount
            FROM
                document d
            WHERE
                d.price_without_high_vat IS NOT NULL AND d.price_without_high_vat <> 0');

        $this->addSql('INSERT INTO document_price (document_id, price_type_id, vat_level_id, amount, vat_amount)
            SELECT
                d.id AS document_id,
                2 AS price_type_id,
                10 AS vat_level_id,
                d.price_without_low_vat AS amount,
                (d.price_without_low_vat * (d.vat_low/100)) AS vat_amount
            FROM
                document d
            WHERE
                d.price_without_low_vat IS NOT NULL AND d.price_without_low_vat <> 0');

        $this->addSql('INSERT INTO document_price (document_id, price_type_id, amount)
            SELECT
                d.id AS document_id,
                1 AS price_type_id,
                d.price_total AS amount
            FROM
                document d
            WHERE
                d.price_total IS NOT NULL');
    }

    public function down(Schema $schema): void
    {

    }
}
