<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124143808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bankAccount CHANGE IDbankAccount id INT NOT NULL');
        $this->addSql('alter table bankAccount modify id int auto_increment');
        $this->addSql('alter table bankAccount auto_increment = 1');
    }

    public function down(Schema $schema): void
    {

    }
}
