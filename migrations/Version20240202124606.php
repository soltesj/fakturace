<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240202124606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bank_account
        CHANGE company_id company_id INT DEFAULT NULL,
        CHANGE short_name short_name VARCHAR(255) NOT NULL,
        CHANGE name name VARCHAR(255) NOT NULL,
        CHANGE number number VARCHAR(255) NOT NULL,
        CHANGE code code VARCHAR(255) DEFAULT NULL,
        CHANGE iban iban VARCHAR(255) DEFAULT NULL,
        CHANGE bic bic VARCHAR(255) DEFAULT NULL,
        CHANGE token token VARCHAR(512) DEFAULT NULL,
        CHANGE routing routing VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_53A23E0A979B1AD6 ON bank_account (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A979B1AD6');
        $this->addSql('DROP INDEX IDX_53A23E0A979B1AD6 ON bank_account');
        $this->addSql('ALTER TABLE bank_account CHANGE company_id company_id INT NOT NULL, CHANGE short_name short_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE name name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE number number VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE code code VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE iban iban VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE bic bic VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE token token VARCHAR(512) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE routing routing VARCHAR(32) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
    }
}
