<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220103539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_numbers CHANGE company_id company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_numbers ADD CONSTRAINT FK_D3F16C25979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_D3F16C25979B1AD6 ON document_numbers (company_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_numbers DROP FOREIGN KEY FK_D3F16C25979B1AD6');
        $this->addSql('DROP INDEX IDX_D3F16C25979B1AD6 ON document_numbers');
        $this->addSql('ALTER TABLE document_numbers CHANGE company_id company_id INT NOT NULL');
    }
}
