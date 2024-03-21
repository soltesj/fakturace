<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20240321105202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql("INSERT INTO status (id, name) VALUES (1,'STATUS_ACTIVE'), (2,'STATUS_INACTIVE'), (3,'STATUS_ARCHIVED'), (4,'STATUS_DELETED')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE status');
    }
}
