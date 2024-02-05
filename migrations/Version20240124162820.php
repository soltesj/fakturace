<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20240124162820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME table bankAccountBalance to bank_account_balance');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('RENAME table bank_account_balance to bankAccountBalance');
    }
}
