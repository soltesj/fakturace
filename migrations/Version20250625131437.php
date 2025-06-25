<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625131437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE country SET is_eu = 1 WHERE id in(172,20,34,77,112,41,43,50,56,57,148,177,125,81,84,121,120,122,131,155,170,171,174,189,190,209,210);
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE country SET is_eu = 0 WHERE id in(172,20,34,77,112,41,43,50,56,57,148,177,125,81,84,121,120,122,131,155,170,171,174,189,190,209,210);
        SQL
        );
    }
}
