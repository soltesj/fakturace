<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620104706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE house_number house_number VARCHAR(25) NOT NULL, CHANGE town town VARCHAR(255) NOT NULL, CHANGE zipcode zipcode VARCHAR(10) NOT NULL, CHANGE web website VARCHAR(255) DEFAULT NULL
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE house_number house_number VARCHAR(25) DEFAULT NULL, CHANGE town town VARCHAR(255) DEFAULT NULL, CHANGE zipcode zipcode VARCHAR(10) DEFAULT NULL, CHANGE website web VARCHAR(255) DEFAULT NULL
        SQL
        );
    }
}
