<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'document_item')]
#[ORM\Entity]
class DocumentItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'documentItems')]
    private ?Document $document = null;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name = '';

    #[ORM\Column(name: 'quantity', type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $quantity = '1.00';

    #[ORM\Column(name: 'unit', type: Types::STRING, length: 10, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(name: 'price', type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $price;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $vatAmount = null;

    #[ORM\Column(name: 'price_with_vat', type: Types::BOOLEAN, nullable: true)]
    private ?bool $priceWithVat = null;

    #[ORM\ManyToOne]
    private ?VatLevel $vat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): void
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getVatAmount(): ?int
    {
        return $this->vatAmount;
    }

    public function setVatAmount(?int $vatAmount): void
    {
        $this->vatAmount = $vatAmount;
    }

    public function isPriceWithVat(): ?bool
    {
        return $this->priceWithVat;
    }

    public function setPriceWithVat(?bool $priceWithVat): void
    {
        $this->priceWithVat = $priceWithVat;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getVat(): ?VatLevel
    {
        return $this->vat;
    }

    public function setVat(?VatLevel $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

}