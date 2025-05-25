<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525005011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            INSERT INTO months (start_date, end_date, month)
            WITH RECURSIVE all_months AS (
                SELECT DATE('2000-01-01') AS start_date
                UNION ALL
                SELECT DATE_ADD(start_date, INTERVAL 1 MONTH)
                FROM all_months
                WHERE start_date < '2050-12-01'
            )
            SELECT
                start_date,
                DATE_ADD(start_date, INTERVAL 1 MONTH) AS end_date,
                DATE_FORMAT(start_date, '%Y-%m') AS month
            FROM all_months;
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE months
        SQL
        );
    }
}
