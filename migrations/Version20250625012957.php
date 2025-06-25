<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625012957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO default_email_template (id, content, subject, country_id) VALUES (2, 'Hello,

            Please find attached the invoice no. {number} for your reference.
            
            If you have any questions, feel free to contact us.
            
            Kind regards,
            {username}
            {company}', 'Invoice No. {number}', 194);
            SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
