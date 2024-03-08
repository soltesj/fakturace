<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240308123023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_type ADD default_format VARCHAR(16) NOT NULL');
        $this->addSql("UPDATE document_type SET default_format = 'YY10NNNNNN' WHERE id = 1");
        $this->addSql("UPDATE document_type SET default_format = 'YY20NNNNNN' WHERE id = 2");
        $this->addSql("UPDATE document_type SET default_format = 'YY30NNNNNN' WHERE id = 3");
        $this->addSql("UPDATE document_type SET default_format = 'YY40NNNNNN' WHERE id = 4");
        $this->addSql("UPDATE document_type SET default_format = 'YY50NNNNNN' WHERE id = 5");
        $this->addSql("UPDATE document_type SET default_format = 'YY60NNNNNN' WHERE id = 6");
        $this->addSql("UPDATE document_type SET default_format = 'YY70NNNNNN' WHERE id = 7");
        $this->addSql("UPDATE document_type SET default_format = 'YY80NNNNNN' WHERE id = 8");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document_type DROP default_format');
    }
}
