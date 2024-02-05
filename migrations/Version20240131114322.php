<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240131114322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME table fioTransactionUpload to fio_transaction_upload');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('RENAME table fio_transaction_upload to fioTransactionUpload');
    }
}
