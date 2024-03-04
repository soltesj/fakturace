<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240304220300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (1, 23.00, 'Základní sazba', '1993-01-01', '1994-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (2, 5.00, 'Snížená sazba', '1993-01-01', '2007-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (3, 22.00, 'Základní sazba', '1995-01-01', '2004-04-30', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (4, 19.00, 'Základní sazba', '2004-05-01', '2009-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (5, 9.00, 'Snížená sazba', '2008-01-01', '2009-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (6, 20.00, 'Základní sazba', '2010-01-01', '2012-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (7, 10.00, 'Snížená sazba', '2010-01-01', '2011-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (8, 14.00, 'Snížená sazba', '2012-01-01', '2012-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (9, 21.00, 'Základní sazba', '2013-01-01', null, 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (10, 15.00, 'Snížená sazba', '2013-01-01', '2023-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (11, 10.00, 'Snížená sazba', '2015-01-01', '2023-12-31', 41)");
        $this->addSql("INSERT INTO vat_level (id, vat_amount, name, valid_from, valid_to, country_id) VALUES (12, 12.00, 'Snížená sazba', '2024-01-01', null, 41)");
    }

    public function down(Schema $schema): void
    {
    }
}
