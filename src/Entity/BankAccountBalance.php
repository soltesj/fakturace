<?php

namespace App\Entity;

use App\Repository\BankAccountBalanceRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountBalanceRepository::class)]
class BankAccountBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private BankAccount $bankAccount;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $balance;

    public function __construct(BankAccount $bankAccount, string $balance, ?DateTimeImmutable $date = null)
    {
        $this->bankAccount = $bankAccount;
        $this->balance = $balance;
        $this->date = $date ?? new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankAccount(): BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccount $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): void
    {
        $this->balance = $balance;
    }
}
