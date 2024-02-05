<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentsItem
 */
#[ORM\Table(name: 'payments_item')]
#[ORM\Entity]
class PaymentsItem
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
    #[ORM\Column(name: 'payments_id', type: Types::INTEGER, nullable: false)]
    private int $paymentsId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    /**
     * @var float
     */
    #[ORM\Column(name: 'quantity', type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private float $quantity;

    /**
     * @var string
     */
    #[ORM\Column(name: 'unit', type: Types::STRING, length: 10, nullable: false)]
    private string $unit;

    /**
     * @var float
     */
    #[ORM\Column(name: 'price', type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private float $price;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'vat', type: Types::BOOLEAN, nullable: false)]
    private bool $vat;

    /**
     * @var float
     */
    #[ORM\Column(name: 'discount', type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private float $discount;


}
