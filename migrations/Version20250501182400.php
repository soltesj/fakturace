<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250501182400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE document_type SET default_format = 'YY1000000n' WHERE id = 1");
        $this->addSql("UPDATE document_type SET default_format = 'YY2000000n' WHERE id = 2");
        $this->addSql("UPDATE document_type SET default_format = 'YY3000000n' WHERE id = 3");
        $this->addSql("UPDATE document_type SET default_format = 'YY4000000n' WHERE id = 4");
        $this->addSql("UPDATE document_type SET default_format = 'YY5000000n' WHERE id = 5");
        $this->addSql("UPDATE document_type SET default_format = 'YY6000000n' WHERE id = 6");
        $this->addSql("UPDATE document_type SET default_format = 'YY7000000n' WHERE id = 7");
        $this->addSql("UPDATE document_type SET default_format = 'YY8000000n' WHERE id = 8");
    }

    public function down(Schema $schema): void
    {
    }
}
