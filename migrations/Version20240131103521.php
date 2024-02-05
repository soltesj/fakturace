<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131103521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table document engine = InnoDB');
        $this->addSql('alter table document collate = utf8mb4_unicode_ci');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table document engine = MyISAM');
        $this->addSql('alter table document collate = utf8_general_ci');
    }
}
