<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208152106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_currency (company_id INT NOT NULL, currency_id INT NOT NULL, INDEX IDX_71C19D52979B1AD6 (company_id), INDEX IDX_71C19D5238248176 (currency_id), PRIMARY KEY(company_id, currency_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE company_currency ADD CONSTRAINT FK_71C19D52979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_currency ADD CONSTRAINT FK_71C19D5238248176 FOREIGN KEY (currency_id) REFERENCES currency (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_currency DROP FOREIGN KEY FK_71C19D52979B1AD6');
        $this->addSql('ALTER TABLE company_currency DROP FOREIGN KEY FK_71C19D5238248176');
        $this->addSql('DROP TABLE company_currency');
    }
}
