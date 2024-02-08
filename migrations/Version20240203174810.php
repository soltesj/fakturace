<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240203174810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document CHANGE payment_type_id payment_type_id INT DEFAULT NULL');
        $this->addSql('UPDATE document SET payment_type_id = null WHERE payment_type_id = 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document CHANGE payment_type_id payment_type_id INT NOT NULL');
    }
}
