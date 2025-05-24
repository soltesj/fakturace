<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524000830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE payments_item
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD price DOUBLE PRECISION NOT NULL, ADD document_id INT NOT NULL, DROP documents_id, DROP number, DROP variable_symbol, DROP date_taxable, DROP customer_id, DROP customer_name, DROP customer_contact, DROP customer_street, DROP customer_house_number, DROP customer_town, DROP customer_zipcode, DROP customer_ic, DROP customer_dic, DROP description, DROP high_vat, DROP low_vat, DROP price_high_vat, DROP price_low_vat, DROP price_no_vat, DROP price_total
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD CONSTRAINT FK_6D28840D979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD CONSTRAINT FK_6D28840DC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D28840D979B1AD6 ON payment (company_id)
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D28840DC33F7837 ON payment (document_id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE payments_item (id INT AUTO_INCREMENT NOT NULL, payments_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(10) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, price DOUBLE PRECISION NOT NULL, vat TINYINT(1) NOT NULL, discount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = MyISAM COMMENT = '' 
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D979B1AD6
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DC33F7837
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6D28840D979B1AD6 ON payment
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6D28840DC33F7837 ON payment
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD number VARCHAR(20) NOT NULL, ADD variable_symbol VARCHAR(20) NOT NULL, ADD date_taxable DATE NOT NULL, ADD customer_id INT NOT NULL, ADD customer_name VARCHAR(255) NOT NULL, ADD customer_contact VARCHAR(255) NOT NULL, ADD customer_street VARCHAR(255) NOT NULL, ADD customer_house_number VARCHAR(20) NOT NULL, ADD customer_town VARCHAR(255) NOT NULL, ADD customer_zipcode VARCHAR(6) NOT NULL, ADD customer_ic VARCHAR(12) NOT NULL, ADD customer_dic VARCHAR(15) NOT NULL, ADD description TEXT NOT NULL, ADD low_vat DOUBLE PRECISION NOT NULL, ADD price_high_vat DOUBLE PRECISION NOT NULL, ADD price_low_vat DOUBLE PRECISION NOT NULL, ADD price_no_vat DOUBLE PRECISION NOT NULL, ADD price_total DOUBLE PRECISION NOT NULL, CHANGE document_id documents_id INT NOT NULL, CHANGE price high_vat DOUBLE PRECISION NOT NULL
        SQL
        );
    }
}
