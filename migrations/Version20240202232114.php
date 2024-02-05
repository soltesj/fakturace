<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202232114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('delete from document_item where document_id not in (select id from document)');
        $this->addSql('ALTER TABLE document_item CHANGE document_id document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_item ADD CONSTRAINT FK_B8AFA98DC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_B8AFA98DC33F7837 ON document_item (document_id)');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE document_item DROP FOREIGN KEY FK_B8AFA98DC33F7837');
        $this->addSql('DROP INDEX IDX_B8AFA98DC33F7837 ON document_item');
        $this->addSql('ALTER TABLE document_item CHANGE document_id document_id INT NOT NULL');
    }
}
