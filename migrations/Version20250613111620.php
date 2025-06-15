<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250613111620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE email_template (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, company_id INT NOT NULL, INDEX IDX_9C0600CA979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE email_template ADD CONSTRAINT FK_9C0600CA979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE email_template DROP FOREIGN KEY FK_9C0600CA979B1AD6
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE email_template
        SQL
        );
    }
}
