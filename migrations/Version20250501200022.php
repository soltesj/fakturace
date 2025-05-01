<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501200022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE company CHANGE country_id country_id INT NOT NULL, CHANGE maturity_days maturity_days INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE company_id company_id INT NOT NULL, CHANGE country_id country_id INT NOT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document CHANGE company_id company_id INT NOT NULL, CHANGE description description TEXT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document_numbers CHANGE company_id company_id INT NOT NULL, CHANGE number_format number_format VARCHAR(15) NOT NULL, CHANGE next_number next_number INT NOT NULL
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer CHANGE company_id company_id INT DEFAULT NULL, CHANGE country_id country_id INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document_numbers CHANGE number_format number_format VARCHAR(15) DEFAULT NULL, CHANGE next_number next_number INT DEFAULT NULL, CHANGE company_id company_id INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE company CHANGE maturity_days maturity_days INT NOT NULL, CHANGE country_id country_id INT DEFAULT NULL
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE document CHANGE description description TEXT NOT NULL, CHANGE company_id company_id INT DEFAULT NULL
        SQL
        );
    }
}
