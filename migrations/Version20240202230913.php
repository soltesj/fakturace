<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202230913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE userHasCategory');
        $this->addSql('DROP INDEX bankAccountID ON bank_account_balance');
        $this->addSql('ALTER TABLE company_user DROP FOREIGN KEY FK_CEFECCA7A76ED395');
        $this->addSql('DROP INDEX fk_cefecca7a76ed395 ON company_user');
        $this->addSql('CREATE INDEX IDX_CEFECCA7A76ED395 ON company_user (user_id)');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE userHasCategory (userID INT NOT NULL, categoryID INT NOT NULL, accessID INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_czech_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE company_user DROP FOREIGN KEY FK_CEFECCA7A76ED395');
        $this->addSql('DROP INDEX idx_cefecca7a76ed395 ON company_user');
        $this->addSql('CREATE INDEX FK_CEFECCA7A76ED395 ON company_user (user_id)');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX bankAccountID ON bank_account_balance (bank_account_id)');
    }
}
