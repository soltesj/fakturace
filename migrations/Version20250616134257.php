<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250616134257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO default_email_template (id, content, subject, country_id) VALUES (1, 'Dobrý den,

            v příloze Vám posíláme fakturu č. {number}.

            S pozdravem
            {username}
            {company}', 'Faktura č. {number}', 41);
            SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
