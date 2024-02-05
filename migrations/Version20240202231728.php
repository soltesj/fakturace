<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240202231728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document 
    CHANGE parent_id document_id INT DEFAULT NULL, 
    CHANGE company_id company_id INT DEFAULT NULL, 
    CHANGE document_type_id document_type_id INT DEFAULT NULL, 
    CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document 
    CHANGE document_id parent_id INT NOT NULL, 
    CHANGE company_id company_id INT NOT NULL, 
    CHANGE document_type_id document_type_id TINYINT(1) NOT NULL, 
    CHANGE user_id user_id INT NOT NULL');
    }
}
