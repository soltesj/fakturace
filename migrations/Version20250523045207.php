<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250523045207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE company_inboxes (id INT AUTO_INCREMENT NOT NULL, inbox_identifier VARCHAR(20) NOT NULL, company_id INT NOT NULL, INDEX IDX_9A3DE741979B1AD6 (company_id), UNIQUE INDEX uniq_inbox_identifier (inbox_identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE company_inboxes ADD CONSTRAINT FK_9A3DE741979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE company_inboxes DROP FOREIGN KEY FK_9A3DE741979B1AD6
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE company_inboxes
        SQL
        );
    }
}
