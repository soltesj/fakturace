<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'fio_transaction_upload')]
#[ORM\Entity]
class FioTransactionUpload
{
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private int $company_id;

    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 128, nullable: false)]
    private string $customerName;

    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $date;

    #[ORM\Column(name: 'bank_account_id', type: Types::INTEGER, nullable: false)]
    private int $bankAccountId;

    #[ORM\Column(name: 'account_to', type: Types::STRING, length: 32, nullable: false)]
    private string $accountTo;

    #[ORM\Column(name: 'bank_code', type: Types::STRING, length: 32, nullable: false)]
    private string $bankCode;

    #[ORM\Column(name: 'amount', type: Types::DECIMAL, precision: 18, scale: 0, nullable: false)]
    private string $amount;

    #[ORM\Column(name: 'currency', type: Types::STRING, length: 3, nullable: false)]
    private string $currency;

    #[ORM\Column(name: 'variable_symbol', type: Types::STRING, length: 16, nullable: false)]
    private string $variableSymbol;

    #[ORM\Column(name: 'constant_symbol', type: Types::STRING, length: 16, nullable: false)]
    private string $constantSymbol;

    #[ORM\Column(name: 'specificSymbol', type: Types::STRING, length: 16, nullable: false)]
    private string $specificSymbol;

    #[ORM\Column(name: 'message', type: Types::STRING, length: 140, nullable: false)]
    private string $message;

    #[ORM\Column(name: 'paymentType', type: Types::STRING, length: 8, nullable: false)]
    private string $paymentType;

}