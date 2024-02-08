<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'documents')]
    private ?self $document = null;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: self::class)]
    private Collection $documents;

    #[ORM\ManyToOne]
    private ?Company $company = null;

    #[ORM\ManyToOne]
    private ?DocumentType $documentType = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Customer $customer = null;

    #[ORM\ManyToOne]
    private PaymentType $paymentType;

    #[ORM\ManyToOne]
    private ?Currency $currency;

    #[ORM\Column(name: 'transaction_id', type: Types::STRING, length: 32, nullable: true)]
    private string $transactionId;

    #[ORM\Column(name: 'state_id', type: Types::BOOLEAN, nullable: false)]
    private bool $stateId;

    #[ORM\Column(name: 'transfer_tax', type: Types::BOOLEAN, nullable: false)]
    private bool $transferTax;

    #[ORM\Column(name: 'send', type: Types::BOOLEAN, nullable: true)]
    private ?bool $send = null;

    #[ORM\Column(name: 'currency', type: Types::STRING, length: 4, nullable: false)]
    private string $currencyString;

    #[ORM\Column(name: 'rate', type: Types::FLOAT, precision: 10, scale: 4, nullable: false)]
    private float $rate;

    #[ORM\Column(name: 'document_number', type: Types::STRING, length: 20, nullable: false)]
    private string $documentNumber;

    #[ORM\Column(name: 'variable_symbol', type: Types::STRING, length: 10, nullable: false)]
    private string $variableSymbol;

    #[ORM\Column(name: 'constant_symbol', type: Types::STRING, length: 4, nullable: false)]
    private string $constantSymbol;

    #[ORM\Column(name: 'specific_symbol', type: Types::STRING, length: 10, nullable: false)]
    private string $specificSymbol;

    #[ORM\ManyToOne]
    private ?BankAccount $bankAccount = null;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentItem::class)]
    private Collection $documentItems;
    #[ORM\Column(name: 'bank_routing', type: Types::STRING, length: 32, nullable: true)]
    private ?string $bankRouting = null;

    #[ORM\Column(name: 'bank_account_number', type: Types::STRING, length: 50, nullable: true)]
    private ?string $bankAccountNumber = null;

    #[ORM\Column(name: 'iban', type: Types::STRING, length: 64, nullable: false)]
    private ?string $iban;

    #[ORM\Column(name: 'bic', type: Types::STRING, length: 64, nullable: false)]
    private ?string $bic;

    #[ORM\Column(name: 'date_issue', type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $dateIssue;

    #[ORM\Column(name: 'date_taxable', type: Types::DATE_MUTABLE, nullable: true)]
    private DateTimeInterface $dateTaxable;

    #[ORM\Column(name: 'date_due', type: Types::DATE_MUTABLE, nullable: true)]
    private DateTimeInterface $dateDue;

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

    #[ORM\Column(name: 'customer_dic', type: Types::STRING, length: 20, nullable: false)]
    private ?string $customerDic;

    #[ORM\Column(name: 'description', type: Types::TEXT, length: 65535, nullable: false)]
    private ?string $description;

    #[ORM\Column(name: 'vat_high', type: Types::BOOLEAN, nullable: false)]
    private ?bool $vatHigh;

    #[ORM\Column(name: 'vat_low', type: Types::BOOLEAN, nullable: false)]
    private ?bool $vatLow;

    #[ORM\Column(name: 'price_without_high_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceWithoutHighVat;

    #[ORM\Column(name: 'price_without_low_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceWithoutLowVat;

    #[ORM\Column(name: 'price_no_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceNoVat;

    #[ORM\Column(name: 'priceTotal', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceTotal;

    #[ORM\Column(name: 'tag', type: Types::TEXT, length: 65535, nullable: false)]
    private ?string $tag;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): void
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

    public function isStateId(): bool
    {
        return $this->stateId;
    }

    public function setStateId(bool $stateId): void
    {
        $this->stateId = $stateId;
    }

    public function isTransferTax(): bool
    {
        return $this->transferTax;
    }

    public function setTransferTax(bool $transferTax): void
    {
        $this->transferTax = $transferTax;
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

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
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

    public function getVariableSymbol(): string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(string $variableSymbol): void
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
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

    public function getPriceWithoutHighVat(): float
    {
        return $this->priceWithoutHighVat;
    }

    public function setPriceWithoutHighVat(float $priceWithoutHighVat): void
    {
        $this->priceWithoutHighVat = $priceWithoutHighVat;
    }

    public function getPriceWithoutLowVat(): float
    {
        return $this->priceWithoutLowVat;
    }

    public function setPriceWithoutLowVat(float $priceWithoutLowVat): void
    {
        $this->priceWithoutLowVat = $priceWithoutLowVat;
    }

    public function getPriceNoVat(): float
    {
        return $this->priceNoVat;
    }

    public function setPriceNoVat(float $priceNoVat): void
    {
        $this->priceNoVat = $priceNoVat;
    }

    public function getPriceTotal(): float
    {
        return $this->priceTotal;
    }

    public function setPriceTotal(float $priceTotal): void
    {
        $this->priceTotal = $priceTotal;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
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
}