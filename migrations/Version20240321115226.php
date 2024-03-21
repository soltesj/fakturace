<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321115226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE customer SET status_id = 1 WHERE display = 1");
        $this->addSql("UPDATE customer SET status_id = 2 WHERE display = 0");
        $this->addSql('ALTER TABLE customer DROP display');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD display TINYINT(1) NOT NULL');
        $this->addSql("UPDATE customer SET display = 0");
        $this->addSql("UPDATE customer SET display = 1 WHERE status_id = 1");

    }
}
