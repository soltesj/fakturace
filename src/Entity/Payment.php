<?php

namespace App\Entity;

use App\Enum\PaymentType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
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
    private float $price;

    public function __construct(
        Company $company,
        PaymentType $type,
        DateTimeImmutable $date,
        float $price,
        ?Document $document = null,
        ?BankAccount $bankAccount = null
    ) {
        $this->company = $company;
        $this->type = $type;
        $this->date = $date;
        $this->document = $document;
        $this->price = $price;
        $this->bankAccount = $bankAccount;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
