<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250524044316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ENGINE = InnoDB;

        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE sequence ENGINE = InnoDB;

        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ENGINE = MyISAM;
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE sequence ENGINE = MyISAM;
        SQL
        );
    }
}
