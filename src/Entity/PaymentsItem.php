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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column( type: Types::INTEGER, nullable: false)]
    private ?int $paymentsId = null;

    #[ORM\Column( type: Types::STRING, length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column( type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private ?float $quantity = null;

    #[ORM\Column( type: Types::STRING, length: 10, nullable: false)]
    private ?string $unit = null;

    #[ORM\Column( type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private ?float $price = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private ?bool $vat = null;

    #[ORM\Column( type: Types::FLOAT, precision: 10, scale: 0, nullable: false)]
    private ?float $discount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentsId(): ?int
    {
        return $this->paymentsId;
    }

    public function setPaymentsId(?int $paymentsId): void
    {
        $this->paymentsId = $paymentsId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getVat(): ?bool
    {
        return $this->vat;
    }

    public function setVat(?bool $vat): void
    {
        $this->vat = $vat;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
    }


}
