<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250616134247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE default_email_template (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, country_id INT NOT NULL, INDEX IDX_188E6088F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE default_email_template ADD CONSTRAINT FK_188E6088F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE default_email_template DROP FOREIGN KEY FK_188E6088F92F3E70
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE default_email_template
        SQL
        );
    }
}
