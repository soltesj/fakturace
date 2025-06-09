<?php

namespace App\Entity;

use App\Enum\VatMode;
use App\Repository\DocumentRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Index(name: 'idx_document_company_date', columns: ['company_id', 'date_issue'])]
#[ORM\Index(name: 'idx_document_company_date_due', columns: ['company_id', 'date_due'])]
#[ORM\Index(name: 'idx_document_remaining_amount', columns: ['remaining_amount'])]
#[ORM\Index(name: 'idx_document_remaining_amount', columns: ['remaining_amount'])]
#[UniqueConstraint(name: "UX_company_id_document_number", columns: ['company_id', 'document_number'])]
class Document implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'documents')]
    private ?self $document = null;

    /**
     * @var Collection<int,Document>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'document')]
    private Collection $documents;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\ManyToOne]
    private ?DocumentType $documentType = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'documents')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne]
    private ?DocumentPaymentType $paymentType = null;

    #[ORM\ManyToOne]
    private ?Currency $currency = null;

    #[ORM\Column(name: 'transaction_id', type: Types::STRING, length: 32, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(name: 'state_id', type: Types::INTEGER, nullable: true)]
    private ?int $stateId = null;

    #[ORM\Column(name: 'send', type: Types::BOOLEAN, nullable: true)]
    private ?bool $send = null;

    #[ORM\Column(name: 'currency', type: Types::STRING, length: 4, nullable: true)]
    private ?string $currencyString = null;

    #[ORM\Column(name: 'rate', type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $rate = null;

    #[ORM\Column(name: 'document_number', type: Types::STRING, length: 20, nullable: false)]
    private string $documentNumber;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $variableSymbol = null;

    #[ORM\Column(type: Types::STRING, length: 4, nullable: true)]
    private ?string $constantSymbol = null;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $specificSymbol = null;

    #[ORM\ManyToOne(inversedBy: "documents")]
    private ?BankAccount $bankAccount = null;

    /**
     * @var Collection<int,DocumentItem>
     */
    #[ORM\OneToMany(targetEntity: DocumentItem::class, mappedBy: 'document', cascade: ['persist', 'remove'])]
    private Collection $documentItems;

    /**
     * @var Collection<int,DocumentPrice>
     */
    #[ORM\OneToMany(targetEntity: DocumentPrice::class, mappedBy: 'document', cascade: ['persist', 'remove'])]
    private Collection $documentPrices;

    #[ORM\Column(name: 'bank_routing', type: Types::STRING, length: 32, nullable: true)]
    private ?string $bankRouting = null;

    #[ORM\Column(name: 'bank_account_number', type: Types::STRING, length: 50, nullable: true)]
    private ?string $bankAccountNumber = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true)]
    private ?string $iban = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true)]
    private ?string $bic = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $dateIssue;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateTaxable = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateDue = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $datePaid = null;

    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 255, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(name: 'customer_contact', type: Types::STRING, length: 255, nullable: true)]
    private ?string $customerContact = null;

    #[ORM\Column(name: 'customer_street', type: Types::STRING, length: 255, nullable: true)]
    private ?string $customerStreet = null;

    #[ORM\Column(name: 'customer_house_number', type: Types::STRING, length: 20, nullable: true)]
    private ?string $customerHouseNumber = null;

    #[ORM\Column(name: 'customer_town', type: Types::STRING, length: 255, nullable: true)]
    private ?string $customerTown = null;

    #[ORM\Column(name: 'customer_zipcode', type: Types::STRING, length: 6, nullable: true)]
    private ?string $customerZipcode = null;

    #[ORM\Column(name: 'customer_ic', type: Types::STRING, length: 20, nullable: true)]
    private ?string $customerIc = null;

    #[ORM\Column(name: 'customer_dic', type: Types::STRING, length: 20, nullable: true)]
    private ?string $customerDic = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $vatHigh = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $vatLow = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $priceWithoutHighVat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $priceWithoutLowVat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $priceNoVat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $priceTotal = null;

    #[ORM\Column(type: Types::TEXT, length: 65535, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: VatMode::class)]
    private ?VatMode $vatMode = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $totalAmount = '0.00';
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $remainingAmount = '0.00';

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->documents = new ArrayCollection();
        $this->documentItems = new ArrayCollection();
        $this->documentPrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentType(): ?DocumentPaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?DocumentPaymentType $paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function isStateId(): ?int
    {
        return $this->stateId;
    }

    public function setStateId(?int $stateId): void
    {
        $this->stateId = $stateId;
    }

    public function getSend(): ?bool
    {
        return $this->send;
    }

    public function setSend(?bool $send): void
    {
        $this->send = $send;
    }

    public function getCurrencyString(): string
    {
        return $this->currencyString;
    }

    public function setCurrencyString(string $currencyString): void
    {
        $this->currencyString = $currencyString;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): void
    {
        $this->rate = $rate;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): void
    {
        $this->documentNumber = $documentNumber;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(?string $variableSymbol): void
    {
        $this->variableSymbol = $variableSymbol;
    }

    public function getConstantSymbol(): string
    {
        return $this->constantSymbol;
    }

    public function setConstantSymbol(string $constantSymbol): void
    {
        $this->constantSymbol = $constantSymbol;
    }

    public function getSpecificSymbol(): string
    {
        return $this->specificSymbol;
    }

    public function setSpecificSymbol(string $specificSymbol): void
    {
        $this->specificSymbol = $specificSymbol;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    public function getBankRouting(): ?string
    {
        return $this->bankRouting;
    }

    public function setBankRouting(?string $bankRouting): void
    {
        $this->bankRouting = $bankRouting;
    }

    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    public function setBankAccountNumber(?string $bankAccountNumber): void
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
    }

    public function getBic(): string
    {
        return $this->bic;
    }

    public function setBic(string $bic): void
    {
        $this->bic = $bic;
    }

    public function getDateIssue(): DateTimeInterface
    {
        return $this->dateIssue;
    }

    public function setDateIssue(DateTimeInterface $dateIssue): void
    {
        $this->dateIssue = $dateIssue;
    }

    public function getDateTaxable(): DateTimeInterface
    {
        return $this->dateTaxable;
    }

    public function setDateTaxable(DateTimeInterface $dateTaxable): void
    {
        $this->dateTaxable = $dateTaxable;
    }

    public function getDateDue(): DateTimeInterface
    {
        return $this->dateDue;
    }

    public function setDateDue(DateTimeInterface $dateDue): void
    {
        $this->dateDue = $dateDue;
    }

    public function getDatePaid(): ?DateTimeImmutable
    {
        return $this->datePaid;
    }

    public function isPaid(): bool
    {
        return $this->datePaid !== null;
    }

    public function setDatePaid(?DateTimeImmutable $datePaid): void
    {
        $this->datePaid = $datePaid;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getCustomerContact(): ?string
    {
        return $this->customerContact;
    }

    public function setCustomerContact(?string $customerContact): void
    {
        $this->customerContact = $customerContact;
    }

    public function getCustomerStreet(): ?string
    {
        return $this->customerStreet;
    }

    public function setCustomerStreet(?string $customerStreet): void
    {
        $this->customerStreet = $customerStreet;
    }

    public function getCustomerHouseNumber(): ?string
    {
        return $this->customerHouseNumber;
    }

    public function setCustomerHouseNumber(?string $customerHouseNumber): void
    {
        $this->customerHouseNumber = $customerHouseNumber;
    }

    public function getCustomerTown(): ?string
    {
        return $this->customerTown;
    }

    public function setCustomerTown(?string $customerTown): void
    {
        $this->customerTown = $customerTown;
    }

    public function getCustomerZipcode(): ?string
    {
        return $this->customerZipcode;
    }

    public function setCustomerZipcode(?string $customerZipcode): void
    {
        $this->customerZipcode = $customerZipcode;
    }

    public function getCustomerIc(): ?string
    {
        return $this->customerIc;
    }

    public function setCustomerIc(?string $customerIc): void
    {
        $this->customerIc = $customerIc;
    }

    public function getCustomerDic(): string
    {
        return $this->customerDic;
    }

    public function setCustomerDic(string $customerDic): void
    {
        $this->customerDic = $customerDic;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isVatHigh(): bool
    {
        return $this->vatHigh;
    }

    public function setVatHigh(bool $vatHigh): void
    {
        $this->vatHigh = $vatHigh;
    }

    public function isVatLow(): bool
    {
        return $this->vatLow;
    }

    public function setVatLow(bool $vatLow): void
    {
        $this->vatLow = $vatLow;
    }

    public function getPriceWithoutHighVat(): ?string
    {
        return $this->priceWithoutHighVat;
    }

    public function setPriceWithoutHighVat(?string $priceWithoutHighVat): void
    {
        $this->priceWithoutHighVat = $priceWithoutHighVat;
    }

    public function getPriceWithoutLowVat(): ?string
    {
        return $this->priceWithoutLowVat;
    }

    public function setPriceWithoutLowVat(?string $priceWithoutLowVat): void
    {
        $this->priceWithoutLowVat = $priceWithoutLowVat;
    }

    public function getPriceNoVat(): ?string
    {
        return $this->priceNoVat;
    }

    public function setPriceNoVat(?string $priceNoVat): void
    {
        $this->priceNoVat = $priceNoVat;
    }

    public function getPriceTotal(): ?string
    {
        return $this->priceTotal;
    }

    public function setPriceTotal(?string $priceTotal): void
    {
        $this->priceTotal = $priceTotal;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }


    public function getDocument(): ?self
    {
        return $this->document;
    }

    public function setDocument(?self $document): static
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(self $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setDocument($this);
        }

        return $this;
    }

    public function removeDocument(self $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getDocument() === $this) {
                $document->setDocument(null);
            }
        }

        return $this;
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

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return Collection<int, DocumentItem>
     */
    public function getDocumentItems(): Collection
    {
        return $this->documentItems;
    }

    public function addDocumentItem(DocumentItem $documentItem): static
    {
        if (!$this->documentItems->contains($documentItem)) {
            $this->documentItems->add($documentItem);
            $documentItem->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentItem(DocumentItem $documentItem): static
    {
        if ($this->documentItems->removeElement($documentItem)) {
            // set the owning side to null (unless already changed)
            if ($documentItem->getDocument() === $this) {
                $documentItem->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DocumentPrice>
     */
    public function getDocumentPrices(): Collection
    {
        return $this->documentPrices;
    }

    public function addDocumentPrice(DocumentPrice $documentPrice): static
    {
        if (!$this->documentPrices->contains($documentPrice)) {
            $this->documentPrices->add($documentPrice);
            $documentPrice->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentPrice(DocumentPrice $documentPrice): static
    {
        if ($this->documentPrices->removeElement($documentPrice)) {
            // set the owning side to null (unless already changed)
            if ($documentPrice->getDocument() === $this) {
                $documentPrice->setDocument(null);
            }
        }

        return $this;
    }

    public function getVatMode(): ?VatMode
    {
        return $this->vatMode;
    }

    public function setVatMode(?VatMode $vatMode): void
    {
        $this->vatMode = $vatMode;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getRemainingAmount(): string
    {
        return $this->remainingAmount;
    }

    public function setRemainingAmount(string $remainingAmount): void
    {
        $this->remainingAmount = $remainingAmount;
    }


}