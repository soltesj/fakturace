<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131134022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('alter table numbers engine = InnoDB');
        $this->addSql('alter table numbers collate = utf8mb4_unicode_ci');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table numbers engine = MyISAM');
        $this->addSql('alter table numbers collate = utf8_general_ci');
    }
}
