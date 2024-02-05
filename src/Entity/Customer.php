<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\ManyToOne]
    private Company $company;

    #[ORM\ManyToOne]
    private Country $country;

    #[ORM\Column(name: 'display', type: Types::BOOLEAN, nullable: false)]
    private bool $display;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'contact', type: Types::STRING, length: 255, nullable: true)]
    private string $contact;

    #[ORM\Column(name: 'street', type: Types::STRING, length: 255, nullable: false)]
    private string $street;

    #[ORM\Column(name: 'house_number', type: Types::STRING, length: 25, nullable: true)]
    private string $houseNumber;

    #[ORM\Column(name: 'town', type: Types::STRING, length: 255, nullable: true)]
    private string $town;

    #[ORM\Column(name: 'zipcode', type: Types::STRING, length: 10, nullable: true)]
    private string $zipcode;

    #[ORM\Column(name: 'ic', type: Types::STRING, length: 20, nullable: true)]
    private string $ic;

    #[ORM\Column(name: 'dic', type: Types::STRING, length: 20, nullable: true)]
    private string $dic;

    #[ORM\Column(name: 'phone', type: Types::STRING, length: 25, nullable: true)]
    private string $phone;

    #[ORM\Column(name: 'email', type: Types::STRING, length: 255, nullable: true)]
    private string $email;

    #[ORM\Column(name: 'web', type: Types::STRING, length: 255, nullable: true)]
    private string $web;

    #[ORM\Column(name: 'bank_account', type: Types::STRING, length: 40, nullable: true)]
    private string $bankAccount;

    public function getId(): int
    {
        return $this->id;
    }


    public function isDisplay(): bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): void
    {
        $this->display = $display;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function setContact(string $contact): void
    {
        $this->contact = $contact;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function getTown(): string
    {
        return $this->town;
    }

    public function setTown(string $town): void
    {
        $this->town = $town;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getIc(): string
    {
        return $this->ic;
    }

    public function setIc(string $ic): void
    {
        $this->ic = $ic;
    }

    public function getDic(): string
    {
        return $this->dic;
    }

    public function setDic(string $dic): void
    {
        $this->dic = $dic;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getWeb(): string
    {
        return $this->web;
    }

    public function setWeb(string $web): void
    {
        $this->web = $web;
    }

    public function getBankAccount(): string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
