<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name: 'payments')]
#[ORM\Entity]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;
    
    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private ?int $companyId = null;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, nullable: false)]
    private ?string $type = null;

    #[ORM\Column(name: 'documents_id', type: Types::INTEGER, nullable: false)]
    private ?int $documentsId = null;

    #[ORM\Column(name: 'number', type: Types::STRING, length: 20, nullable: false)]
    private ?string $number = null;

    #[ORM\Column(name: 'variable_symbol', type: Types::STRING, length: 20, nullable: false)]
    private ?string $variableSymbol = null;

    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column(name: 'date_taxable', type: Types::DATE_MUTABLE, nullable: false)]
    private ?DateTimeInterface $dateTaxable = null;

    #[ORM\Column(name: 'customer_id', type: Types::INTEGER, nullable: false)]
    private ?int $customerId = null;

    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 255, nullable: false)]
    private ?string $customerName = null;

    #[ORM\Column(name: 'customer_contact', type: Types::STRING, length: 255, nullable: false)]
    private ?string $customerContact = null;

    #[ORM\Column(name: 'customer_street', type: Types::STRING, length: 255, nullable: false)]
    private ?string $customerStreet = null;

    #[ORM\Column(name: 'customer_house_number', type: Types::STRING, length: 20, nullable: false)]
    private ?string $customerHouseNumber = null;

    #[ORM\Column(name: 'customer_town', type: Types::STRING, length: 255, nullable: false)]
    private ?string $customerTown = null;

    #[ORM\Column(name: 'customer_zipcode', type: Types::STRING, length: 6, nullable: false)]
    private ?string $customerZipcode = null;

    #[ORM\Column(name: 'customer_ic', type: Types::STRING, length: 12, nullable: false)]
    private ?string $customerIc = null;

    #[ORM\Column(name: 'customer_dic', type: Types::STRING, length: 15, nullable: false)]
    private ?string $customerDic = null;

    #[ORM\Column(name: 'description', type: Types::TEXT, length: 65535, nullable: false)]
    private ?string $description = null;

    #[ORM\Column(name: 'high_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $highVat = null;

    #[ORM\Column(name: 'low_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $lowVat = null;

    #[ORM\Column(name: 'price_high_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceHighVat = null;

    #[ORM\Column(name: 'price_low_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceLowVat = null;

    #[ORM\Column(name: 'price_no_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceNoVat = null;

    #[ORM\Column(name: 'price_total', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private ?float $priceTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getDocumentsId(): ?int
    {
        return $this->documentsId;
    }

    public function setDocumentsId(?int $documentsId): void
    {
        $this->documentsId = $documentsId;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(?string $variableSymbol): void
    {
        $this->variableSymbol = $variableSymbol;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getDateTaxable(): DateTimeInterface
    {
        return $this->dateTaxable;
    }

    public function setDateTaxable(DateTimeInterface $dateTaxable): void
    {
        $this->dateTaxable = $dateTaxable;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): void
    {
        $this->customerId = $customerId;
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

    public function getCustomerDic(): ?string
    {
        return $this->customerDic;
    }

    public function setCustomerDic(?string $customerDic): void
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

    public function getHighVat(): ?float
    {
        return $this->highVat;
    }

    public function setHighVat(?float $highVat): void
    {
        $this->highVat = $highVat;
    }

    public function getLowVat(): ?float
    {
        return $this->lowVat;
    }

    public function setLowVat(?float $lowVat): void
    {
        $this->lowVat = $lowVat;
    }

    public function getPriceHighVat(): ?float
    {
        return $this->priceHighVat;
    }

    public function setPriceHighVat(?float $priceHighVat): void
    {
        $this->priceHighVat = $priceHighVat;
    }

    public function getPriceLowVat(): ?float
    {
        return $this->priceLowVat;
    }

    public function setPriceLowVat(?float $priceLowVat): void
    {
        $this->priceLowVat = $priceLowVat;
    }

    public function getPriceNoVat(): ?float
    {
        return $this->priceNoVat;
    }

    public function setPriceNoVat(?float $priceNoVat): void
    {
        $this->priceNoVat = $priceNoVat;
    }

    public function getPriceTotal(): ?float
    {
        return $this->priceTotal;
    }

    public function setPriceTotal(?float $priceTotal): void
    {
        $this->priceTotal = $priceTotal;
    }
}
