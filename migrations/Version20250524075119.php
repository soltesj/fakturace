<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524075119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE bank_account_balance ADD CONSTRAINT FK_9A4739BA12CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9A4739BA12CB990C ON bank_account_balance (bank_account_id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE bank_account_balance DROP FOREIGN KEY FK_9A4739BA12CB990C
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9A4739BA12CB990C ON bank_account_balance
        SQL
        );
    }
}
