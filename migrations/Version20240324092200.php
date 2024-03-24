<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324092200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM document_numbers WHERE document_type_id >= 9');
        $this->addSql('DELETE FROM document_type WHERE id >= 9');
        $this->addSql('alter table document_type auto_increment = 9');
    }

    public function down(Schema $schema): void
    {
    }
}
