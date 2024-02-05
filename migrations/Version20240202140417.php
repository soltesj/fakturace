<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240202140417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bank_account 
    CHANGE number account_number VARCHAR(255) NOT NULL, 
    CHANGE code bank_code VARCHAR(255) DEFAULT NULL, 
    CHANGE routing routing_number VARCHAR(32) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bank_account CHANGE account_number number VARCHAR(255) NOT NULL, CHANGE bank_code code VARCHAR(255) DEFAULT NULL, CHANGE routing_number routing VARCHAR(32) DEFAULT NULL');
    }
}
