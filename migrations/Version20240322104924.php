<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322104924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_53A23E0A6BF700BD ON bank_account (status_id)');
        $this->addSql("UPDATE bank_account SET status_id = 1");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A6BF700BD');
        $this->addSql('DROP INDEX IDX_53A23E0A6BF700BD ON bank_account');
        $this->addSql('ALTER TABLE bank_account DROP status_id');
    }
}
