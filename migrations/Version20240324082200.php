<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324082200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM document_item WHERE document_id in (SELECT id from document where document_type_id >= 9)');
        $this->addSql('SET FOREIGN_KEY_CHECKS=0; DELETE FROM document WHERE document_type_id >=9; SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
    }
}
