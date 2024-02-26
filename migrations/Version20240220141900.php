<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240220141900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sql= "UPDATE fakturace.document SET document_number = concat('X',id,'.',document_number) where id in(
    SELECT id from document GROUP BY document_number, company_id HAVING COUNT(*) > 1
    ) or document_number = ''";
        while ($this->connection->executeQuery($sql)->rowCount() > 0) {
        }
    }

    public function down(Schema $schema): void
    {

    }
}
