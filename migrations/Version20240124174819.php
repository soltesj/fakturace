<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124174819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('DROP INDEX user_id ON company_has_user');
        $this->addSql('ALTER TABLE company_has_user 
    CHANGE companyID company_id INT NOT NULL, 
    CHANGE userID user_id INT NOT NULL
    ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company_has_user 
        CHANGE company_id companyID INT NOT NULL, 
        CHANGE user_id userID INT NOT NULL');
        $this->addSql('CREATE INDEX user_id ON company_has_user (companyID, userID)');
    }
}
