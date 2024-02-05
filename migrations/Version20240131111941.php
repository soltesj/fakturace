<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131111941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE documentThread');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documentThread (IDdocumentThread INT AUTO_INCREMENT NOT NULL, documentID INT NOT NULL, userID INT NOT NULL, date DATETIME NOT NULL, description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, PRIMARY KEY(IDdocumentThread)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_czech_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
