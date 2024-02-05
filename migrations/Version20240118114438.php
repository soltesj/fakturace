<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118114438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documentThread CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(64) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(128) NOT NULL, CHANGE block block TINYINT(1) NOT NULL, CHANGE sendEmail sendEmail TINYINT(1) DEFAULT NULL, CHANGE registerDate registerDate DATETIME NOT NULL, CHANGE lastVisitDate lastVisitDate DATETIME NOT NULL, CHANGE activation activation VARCHAR(100) DEFAULT NULL, CHANGE resetTime resetTime DATETIME NOT NULL COMMENT \'Date of last password reset\', CHANGE resetCount resetCount INT NOT NULL COMMENT \'Count of password resets since lastResetTime\'');
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('ALTER TABLE usersSetting CHANGE userId userId INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE documentThread CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE usersSetting CHANGE userId userId INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE username username VARCHAR(64) DEFAULT \'\' NOT NULL, CHANGE email email VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE password password VARCHAR(128) DEFAULT \'\' NOT NULL, CHANGE block block TINYINT(1) DEFAULT 0 NOT NULL, CHANGE sendEmail sendEmail TINYINT(1) DEFAULT 0, CHANGE registerDate registerDate DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, CHANGE lastVisitDate lastVisitDate DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, CHANGE activation activation VARCHAR(100) DEFAULT \'\', CHANGE resetTime resetTime DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'Date of last password reset\', CHANGE resetCount resetCount INT DEFAULT 0 NOT NULL COMMENT \'Count of password resets since lastResetTime\'');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON user');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
    }
}
