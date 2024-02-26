<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240220102256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME table numbers to document_numbers');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('RENAME table document_numbers to numbers');
    }
}
