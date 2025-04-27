<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    private Country $country;

    #[ORM\Column(name: 'vat_payer', type: Types::BOOLEAN, nullable: false)]
    private bool $vatPayer = false;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'alias', type: Types::STRING, length: 255, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(name: 'contact', type: Types::STRING, length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(name: 'street', type: Types::STRING, length: 128, nullable: false)]
    private string $street;

    #[ORM\Column(name: 'building_number', type: Types::STRING, length: 16, nullable: false)]
    private string $buildingNumber;

    #[ORM\Column(name: 'city', type: Types::STRING, length: 128, nullable: false)]
    private string $city;

    #[ORM\Column(name: 'zipcode', type: Types::STRING, length: 6, nullable: false)]
    private string $zipcode;

    #[ORM\Column(name: 'ic', type: Types::STRING, length: 16, nullable: false)]
    private string $ic;

    #[ORM\Column(name: 'dic', type: Types::STRING, length: 16, nullable: false)]
    private string $dic;

    #[ORM\Column(name: 'info', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(name: 'phone', type: Types::STRING, length: 25, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(name: 'email', type: Types::STRING, length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'website', type: Types::STRING, length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(name: 'email_invoice_message', type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $emailInvoiceMessage = null;

    /**
     * @var Collection<int,User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'companies', cascade: ['persist'])]
    private Collection $users;

    /**
     * @var Collection<int,Currency>
     */
    #[ORM\ManyToMany(targetEntity: Currency::class)]
    private Collection $currency;

    #[ORM\Column]
    private ?int $maturityDays = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->currency = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCompanies($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCompany($this);
        }

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function isVatPayer(): bool
    {
        return $this->vatPayer;
    }

    public function setVatPayer(bool $vatPayer): void
    {
        $this->vatPayer = $vatPayer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): void
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

    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
    }

    public function setBuildingNumber(string $buildingNumber): void
    {
        $this->buildingNumber = $buildingNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
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

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): void
    {
        $this->info = $info;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    public function getEmailInvoiceMessage(): ?string
    {
        return $this->emailInvoiceMessage;
    }

    public function setEmailInvoiceMessage(?string $emailInvoiceMessage): void
    {
        $this->emailInvoiceMessage = $emailInvoiceMessage;
    }

    /**
     * @return Collection<int, Currency>
     */
    public function getCurrency(): Collection
    {
        return $this->currency;
    }

    public function addCurrency(Currency $currency): static
    {
        if (!$this->currency->contains($currency)) {
            $this->currency->add($currency);
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): static
    {
        $this->currency->removeElement($currency);

        return $this;
    }

    public function getMaturityDays(): ?int
    {
        return $this->maturityDays;
    }

    public function setMaturityDays(?int $maturityDays): static
    {
        $this->maturityDays = $maturityDays;

        return $this;
    }

}
