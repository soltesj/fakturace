<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124130845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP username, DROP salt, DROP state, DROP block, DROP sendEmail, DROP registerDate, DROP lastVisitDate, DROP activation, DROP params, DROP resetTime, DROP resetCount, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE phone phone VARCHAR(16) DEFAULT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD username VARCHAR(64) NOT NULL, ADD salt VARCHAR(128) NOT NULL, ADD state TINYINT(1) NOT NULL, ADD block TINYINT(1) NOT NULL, ADD sendEmail TINYINT(1) DEFAULT NULL, ADD registerDate DATETIME NOT NULL, ADD lastVisitDate DATETIME NOT NULL, ADD activation VARCHAR(100) DEFAULT NULL, ADD params TEXT NOT NULL, ADD resetTime DATETIME NOT NULL COMMENT \'Date of last password reset\', ADD resetCount INT NOT NULL COMMENT \'Count of password resets since lastResetTime\', DROP roles, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(128) NOT NULL, CHANGE surname surname VARCHAR(120) NOT NULL, CHANGE phone phone VARCHAR(16) NOT NULL');
    }
}
