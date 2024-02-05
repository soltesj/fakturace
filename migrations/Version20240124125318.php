<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124125318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user CHANGE IDuser id INT NOT NULL');
//        $this->addSql('alter table user add constraint user_pk primary key (id)');
        $this->addSql('alter table user modify id int auto_increment');
        $this->addSql('alter table user auto_increment = 1');
    }

    public function down(Schema $schema): void
    {

    }
}
