<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625014829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE user set roles='["ROLE_USER","ROLE_COMPANY_EDITOR", "ROLE_COMPANY_ADMIN"]' WHERE 1
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
