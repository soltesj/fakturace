<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240131115938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table fio_transaction_upload engine = InnoDB');
        $this->addSql('alter table fio_transaction_upload collate = utf8mb4_unicode_ci');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table fio_transaction_upload engine = MyISAM');
        $this->addSql('alter table fio_transaction_upload collate = utf8_general_ci');
    }
}
