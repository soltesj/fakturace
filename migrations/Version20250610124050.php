<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610124050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DELETE dp
             FROM document_price dp
             INNER JOIN document d ON dp.document_id = d.id
             WHERE d.company_id = :companyId
        SQL, ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE di
             FROM document_item di
             INNER JOIN document d ON di.document_id = d.id
             WHERE d.company_id = :companyId
        SQL, ['companyId' => 5]);
        $this->addSql(<<<'SQL'
                DELETE FROM document WHERE company_id = :companyId AND document_id IS NOT NULL ;
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
                DELETE FROM document WHERE company_id = :companyId;
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM customer WHERE company_id = :companyId;
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM bank_account WHERE company_id = :companyId;
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM user WHERE id = 5;
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM company_user WHERE company_id = :companyId
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM document_numbers WHERE company_id = :companyId
        SQL
            , ['companyId' => 5]);
        $this->addSql(<<<'SQL'
            DELETE FROM company WHERE id = :companyId
        SQL
            , ['companyId' => 5]);
    }

    public function down(Schema $schema): void
    {
    }
}
