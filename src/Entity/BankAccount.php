<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    private ?Company $company = null;

    #[ORM\Column(name: 'sequence', type: Types::SMALLINT, nullable: false)]
    private ?int $sequence;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $shortName;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private ?string $name;

    #[ORM\Column(length: 255)]
    private ?string $accountNumber;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bankCode;

    #[ORM\Column(name: 'iban', type: Types::STRING, length: 255, nullable: true)]
    private ?string $iban;

    #[ORM\Column(name: 'bic', type: Types::STRING, length: 255, nullable: true)]
    private ?string $bic;

    #[ORM\Column(name: 'token', type: Types::STRING, length: 512, nullable: true)]
    private ?string $token;

    #[ORM\Column( length: 32, nullable: true)]
    private ?string $routingNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(?int $sequence): void
    {
        $this->sequence = $sequence;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): void
    {
        $this->shortName = $shortName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function setBankCode(?string $bankCode): void
    {
        $this->bankCode = $bankCode;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): void
    {
        $this->iban = $iban;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): void
    {
        $this->bic = $bic;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getRoutingNumber(): ?string
    {
        return $this->routingNumber;
    }

    public function setRoutingNumber(?string $routingNumber): void
    {
        $this->routingNumber = $routingNumber;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
