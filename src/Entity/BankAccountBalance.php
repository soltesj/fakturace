<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BankAccountBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $bankAccountId;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $closingBalance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankAccountId(): ?int
    {
        return $this->bankAccountId;
    }

    public function setBankAccountId(int $bankAccountId): void
    {
        $this->bankAccountId = $bankAccountId;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getClosingBalance(): ?string
    {
        return $this->closingBalance;
    }

    public function setClosingBalance(string $closingBalance): void
    {
        $this->closingBalance = $closingBalance;
    }



}
