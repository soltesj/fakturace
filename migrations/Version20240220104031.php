<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220104031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_numbers CHANGE document_type_id document_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_numbers ADD CONSTRAINT FK_D3F16C2561232A4F FOREIGN KEY (document_type_id) REFERENCES document_type (id)');
        $this->addSql('CREATE INDEX IDX_D3F16C2561232A4F ON document_numbers (document_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_numbers DROP FOREIGN KEY FK_D3F16C2561232A4F');
        $this->addSql('DROP INDEX IDX_D3F16C2561232A4F ON document_numbers');
        $this->addSql('ALTER TABLE document_numbers CHANGE document_type_id document_type_id VARCHAR DEFAULT NULL');
    }
}
