<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250622231040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, theme VARCHAR(32) DEFAULT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C779A692A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE user_setting
        SQL
        );
    }
}
