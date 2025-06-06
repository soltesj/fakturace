<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Ulid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606101754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $filmIds = $this->connection->fetchFirstColumn('SELECT id FROM company');
        foreach ($filmIds as $id) {
            $ulid = (string)new Ulid();
            $this->addSql(
                'UPDATE company SET public_id = :publicId WHERE id = :id',
                ['publicId' => $ulid, 'id' => $id]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_4FBF094FB5B48B91 ON company
        SQL
        );
    }
}
