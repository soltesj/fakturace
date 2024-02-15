<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214033639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_item ADD vat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_item ADD CONSTRAINT FK_B8AFA98DB5B63A6B FOREIGN KEY (vat_id) REFERENCES vat_level (id)');
        $this->addSql('CREATE INDEX IDX_B8AFA98DB5B63A6B ON document_item (vat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_item DROP FOREIGN KEY FK_B8AFA98DB5B63A6B');
        $this->addSql('DROP INDEX IDX_B8AFA98DB5B63A6B ON document_item');
        $this->addSql('ALTER TABLE document_item DROP vat_id');
    }
}
