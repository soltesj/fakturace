<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    private Company $company;

    #[ORM\ManyToOne]
    private Country $country;

    /**
     * @var Collection<int,Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'customer', fetch: 'EXTRA_LAZY')]
    private Collection $documents;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $street = null;

    #[ORM\Column(type: Types::STRING, length: 25, nullable: true)]
    private ?string $houseNumber = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $town = null;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $companyNumber = null;

    #[ORM\Column( type: Types::STRING, length: 20, nullable: true)]
    private ?string $vatNumber = null;

    #[ORM\Column(type: Types::STRING, length: 25, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $web = null;

    #[ORM\Column(type: Types::STRING, length: 40, nullable: true)]
    private ?string $bankAccount = null;

    #[ORM\ManyToOne]
    private ?Status $status = null;

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->documents = new ArrayCollection();
    }

    public function __clone(): void
    {
        $this->id = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): void
    {
        $this->contact = $contact;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): void
    {
        $this->town = $town;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getCompanyNumber(): ?string
    {
        return $this->companyNumber;
    }

    public function setCompanyNumber(?string $companyNumber): void
    {
        $this->companyNumber = $companyNumber;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
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

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): void
    {
        $this->web = $web;
    }

    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): static
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setCustomer($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getCustomer() === $this) {
                $document->setCustomer(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }
}
