<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250610205344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
           INSERT INTO customer (
              company_id,
              name,
              contact,
              street,
              house_number,
              town,
              zipcode,
              company_number,
              vat_number,
              is_vat_payer,
              country_id
            )
            SELECT
              tmp.company_id,
              tmp.name,
              tmp.contact,
              tmp.street,
              tmp.house_number,
              tmp.town,
              tmp.zipcode,
              tmp.company_number,
              tmp.vat_number,
              IF(tmp.vat_number IS NOT NULL AND tmp.vat_number != '', 1, 0) AS is_vat_payer,
              41 AS country_id
            FROM (
              SELECT DISTINCT
                d.company_id,
                d.customer_name AS name,
                d.customer_contact AS contact,
                d.customer_street AS street,
                d.customer_house_number AS house_number,
                d.customer_town AS town,
                d.customer_zipcode AS zipcode,
                d.customer_ic AS company_number,
                d.customer_dic AS vat_number
              FROM document d
              WHERE d.customer_id IS NULL
                AND (
                  d.customer_ic IS NOT NULL OR d.customer_name IS NOT NULL
                )
            ) AS tmp
            LEFT JOIN customer c
              ON c.company_id = tmp.company_id
                 AND (
                   (tmp.company_number IS NOT NULL AND tmp.company_number = c.company_number)
                   OR
                   (tmp.company_number IS NULL AND tmp.name = c.name)
                 )
            WHERE c.id IS NULL;
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
