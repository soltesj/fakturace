<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Payments
 */
#[ORM\Table(name: 'payments')]
#[ORM\Entity]
class Payments
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    /**
     * @var int
     */
    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private int $companyId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, nullable: false)]
    private string $type;

    /**
     * @var int
     */
    #[ORM\Column(name: 'documents_id', type: Types::INTEGER, nullable: false)]
    private int $documentsId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'number', type: Types::STRING, length: 20, nullable: false)]
    private string $number;

    /**
     * @var string
     */
    #[ORM\Column(name: 'variable_symbol', type: Types::STRING, length: 20, nullable: false)]
    private string $variableSymbol;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $date;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(name: 'date_taxable', type: Types::DATE_MUTABLE, nullable: false)]
    private DateTimeInterface $dateTaxable;

    /**
     * @var int
     */
    #[ORM\Column(name: 'customer_id', type: Types::INTEGER, nullable: false)]
    private int $customerId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 255, nullable: false)]
    private string $customerName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_contact', type: Types::STRING, length: 255, nullable: false)]
    private string $customerContact;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_street', type: Types::STRING, length: 255, nullable: false)]
    private string $customerStreet;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_house_number', type: Types::STRING, length: 20, nullable: false)]
    private string $customerHouseNumber;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_town', type: Types::STRING, length: 255, nullable: false)]
    private string $customerTown;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_zipcode', type: Types::STRING, length: 6, nullable: false)]
    private string $customerZipcode;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_ic', type: Types::STRING, length: 12, nullable: false)]
    private string $customerIc;

    /**
     * @var string
     */
    #[ORM\Column(name: 'customer_dic', type: Types::STRING, length: 15, nullable: false)]
    private string $customerDic;

    /**
     * @var string
     */
    #[ORM\Column(name: 'description', type: Types::TEXT, length: 65535, nullable: false)]
    private string $description;

    /**
     * @var float
     */
    #[ORM\Column(name: 'high_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $highVat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'low_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $lowVat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'price_high_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $priceHighVat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'price_low_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $priceLowVat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'price_no_vat', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $priceNoVat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'price_total', type: Types::FLOAT, precision: 10, scale: 2, nullable: false)]
    private float $priceTotal;


}
