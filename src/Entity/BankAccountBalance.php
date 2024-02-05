<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'bank_account_balance')]
#[ORM\Entity]
class BankAccountBalance
{
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    /**
     * @var int
     */
    #[ORM\Column(name: 'bank_account_id', type: Types::INTEGER, nullable: false)]
    private int $bankAccountId;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $date;

    /**
     * @var string
     */
    #[ORM\Column(name: 'closing_balance', type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $closingBalance;


}
