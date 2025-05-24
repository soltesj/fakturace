<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524062507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD bank_account_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE document_id document_id INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD CONSTRAINT FK_6D28840D12CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D28840D12CB990C ON payment (bank_account_id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D12CB990C
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6D28840D12CB990C ON payment
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP bank_account_id, CHANGE type type VARCHAR(10) NOT NULL, CHANGE document_id document_id INT NOT NULL
        SQL
        );
    }
}
