<?php

namespace App\Entity;

use App\Enum\PaymentType;
use App\Repository\PaymentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: PaymentType::class)]
    private PaymentType $type;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Document $document;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?BankAccount $bankAccount;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $amount;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $variableSymbol;

    #[ORM\Column(type: Types::STRING, length: 4, nullable: true)]
    private ?string $constantSymbol;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $specificSymbol;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $counterAccount;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $transactionId = null;


    public function __construct(
        Company $company,
        PaymentType $type,
        float $amount,
        ?DateTimeImmutable $date = null,
        ?Document $document = null,
        ?BankAccount $bankAccount = null,
        ?string $variableSymbol = null,
        ?string $constantSymbol = null,
        ?string $specificSymbol = null,
        ?string $counterAccount = null,
        ?string $message = null,
        ?string $transactionId = null,
    ) {

        $this->company = $company;
        $this->type = $type;
        $this->document = $document;
        $this->amount = $amount;
        $this->bankAccount = $bankAccount;
        $this->variableSymbol = $variableSymbol;
        $this->constantSymbol = $constantSymbol;
        $this->specificSymbol = $specificSymbol;
        $this->counterAccount = $counterAccount;
        $this->message = $message;
        $this->transactionId = $transactionId;
        $this->date = $date ?: new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getType(): PaymentType
    {
        return $this->type;
    }

    public function setType(?PaymentType $type): void
    {
        $this->type = $type;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): void
    {
        $this->document = $document;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
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

    public function getCounterAccount(): ?string
    {
        return $this->counterAccount;
    }

    public function setCounterAccount(?string $counterAccount): void
    {
        $this->counterAccount = $counterAccount;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }
}
