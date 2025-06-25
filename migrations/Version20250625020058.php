<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250625020058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            UPDATE document
            SET currency_id =
                    CASE
                        WHEN currency = 'CZK' THEN 44
                        WHEN currency = 'EUR' THEN 53
                        WHEN currency = '$' THEN 161
                        END
            WHERE 1
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            
        SQL
        );
    }
}
