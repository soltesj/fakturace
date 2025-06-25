<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\VatPaymentMode;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250624105750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $filmIds = $this->connection->fetchFirstColumn('SELECT id FROM company WHERE is_vat_payer = 1');
        foreach ($filmIds as $id) {
            $this->addSql(
                'UPDATE company SET vat_payment_mode = :vatPaymentMode WHERE id = :id',
                ['vatPaymentMode' => VatPaymentMode::MONTHLY->value, 'id' => $id]
            );
        }
    }

    public function down(Schema $schema): void
    {
    }
}
