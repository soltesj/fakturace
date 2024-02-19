<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20240215173222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document CHANGE variable_symbol variable_symbol VARCHAR(10) DEFAULT NULL, CHANGE constant_symbol constant_symbol VARCHAR(4) DEFAULT NULL, CHANGE specific_symbol specific_symbol VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document CHANGE variable_symbol variable_symbol VARCHAR(10) NOT NULL, CHANGE constant_symbol constant_symbol VARCHAR(4) NOT NULL, CHANGE specific_symbol specific_symbol VARCHAR(10) NOT NULL');
    }
}
