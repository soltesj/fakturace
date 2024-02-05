<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203010103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE document SET bank_account_id = null where bank_account_id not in (select id from bank_account)');
        $this->addSql('ALTER TABLE document CHANGE bank_account bank_account_number VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7612CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('CREATE INDEX IDX_D8698A7612CB990C ON document (bank_account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7612CB990C');
        $this->addSql('DROP INDEX IDX_D8698A7612CB990C ON document');
        $this->addSql('ALTER TABLE document CHANGE bank_account_number bank_account VARCHAR(50) DEFAULT NULL');
    }
}
