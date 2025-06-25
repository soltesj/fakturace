<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625135829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document
                LEFT JOIN customer ON document.customer_id = customer.id
                LEFT JOIN company ON document.company_id = company.id
            SET document.vat_mode = CASE
                                        WHEN company.is_vat_payer = 1 AND customer.country_id = company.country_id THEN 'domestic'
                                        WHEN company.is_vat_payer = 1 AND customer.country_id != company.country_id THEN 'reverse_charge'
                                        ELSE 'none'
                END
            WHERE document.vat_mode is null
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
