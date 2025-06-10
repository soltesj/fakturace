<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610211427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP customer_name, DROP customer_contact, DROP customer_street, DROP customer_house_number, DROP customer_town, DROP customer_zipcode, DROP customer_ic, DROP customer_dic
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD customer_name VARCHAR(255) DEFAULT NULL, ADD customer_contact VARCHAR(255) DEFAULT NULL, ADD customer_street VARCHAR(255) DEFAULT NULL, ADD customer_house_number VARCHAR(20) DEFAULT NULL, ADD customer_town VARCHAR(255) DEFAULT NULL, ADD customer_zipcode VARCHAR(6) DEFAULT NULL, ADD customer_ic VARCHAR(20) DEFAULT NULL, ADD customer_dic VARCHAR(20) DEFAULT NULL
        SQL
        );
    }
}
