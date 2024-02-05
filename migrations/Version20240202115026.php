<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202115026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, navigation_id INT NOT NULL, language VARCHAR(7) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, state TINYINT(1) NOT NULL, property SMALLINT NOT NULL, attribs VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, created DATETIME NOT NULL, created_by INT NOT NULL, modified DATETIME NOT NULL, modified_by INT NOT NULL, ordering INT NOT NULL, publish_up DATETIME NOT NULL, publish_down DATETIME NOT NULL, title TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, title_alias VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, introtext TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, `fulltext` TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, metakey TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, metadesc TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, INDEX id_menu (navigation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
    }
}
