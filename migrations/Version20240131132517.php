<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131132517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE navigation');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE navigation (id INT AUTO_INCREMENT NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, depth INT NOT NULL, display SMALLINT NOT NULL, name TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, breadcrumbs TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, url TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, engin_file TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_czech_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_czech_ci` ENGINE = MyISAM COMMENT = \'\' ');
    }
}
