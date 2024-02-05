<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202231329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    public function up(Schema $schema): void
    {
        $this->addSql('alter table document_type engine = InnoDB');
        $this->addSql('alter table document_type collate = utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE document_type CHANGE name name VARCHAR(128) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table document_type engine = MyISAM');
        $this->addSql('alter table document_type collate = utf8_general_ci');
        $this->addSql('ALTER TABLE document_type CHANGE name name VARCHAR(128) CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`');
    }
}
