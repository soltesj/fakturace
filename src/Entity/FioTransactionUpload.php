<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'fio_transaction_upload')]
#[ORM\Entity]
class FioTransactionUpload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private ?int $companyId = null;

    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 128, nullable: false)]
    private ?string $customerName = null;

    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column(name: 'bank_account_id', type: Types::INTEGER, nullable: false)]
    private ?int $bankAccountId = null;

    #[ORM\Column(name: 'account_to', type: Types::STRING, length: 32, nullable: false)]
    private ?string $accountTo = null;

    #[ORM\Column(name: 'bank_code', type: Types::STRING, length: 32, nullable: false)]
    private ?string $bankCode = null;

    #[ORM\Column(name: 'amount', type: Types::DECIMAL, precision: 18, scale: 0, nullable: false)]
    private ?string $amount = null;

    #[ORM\Column(name: 'currency', type: Types::STRING, length: 3, nullable: false)]
    private ?string $currency = null;

    #[ORM\Column(name: 'variable_symbol', type: Types::STRING, length: 16, nullable: false)]
    private ?string $variableSymbol = null;

    #[ORM\Column(name: 'constant_symbol', type: Types::STRING, length: 16, nullable: false)]
    private ?string $constantSymbol = null;

    #[ORM\Column(name: 'specificSymbol', type: Types::STRING, length: 16, nullable: false)]
    private ?string $specificSymbol = null;

    #[ORM\Column(name: 'message', type: Types::STRING, length: 140, nullable: false)]
    private ?string $message = null;

    #[ORM\Column(name: 'paymentType', type: Types::STRING, length: 8, nullable: false)]
    private ?string $paymentType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getBankAccountId(): ?int
    {
        return $this->bankAccountId;
    }

    public function setBankAccountId(?int $bankAccountId): void
    {
        $this->bankAccountId = $bankAccountId;
    }

    public function getAccountTo(): ?string
    {
        return $this->accountTo;
    }

    public function setAccountTo(?string $accountTo): void
    {
        $this->accountTo = $accountTo;
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function setBankCode(?string $bankCode): void
    {
        $this->bankCode = $bankCode;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(?string $variableSymbol): void
    {
        $this->variableSymbol = $variableSymbol;
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol;
    }

    public function setConstantSymbol(?string $constantSymbol): void
    {
        $this->constantSymbol = $constantSymbol;
    }

    public function getSpecificSymbol(): ?string
    {
        return $this->specificSymbol;
    }

    public function setSpecificSymbol(?string $specificSymbol): void
    {
        $this->specificSymbol = $specificSymbol;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(?string $paymentType): void
    {
        $this->paymentType = $paymentType;
    }

}