<?php

namespace App\Entity;

use App\Repository\DocumentPriceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentPriceRepository::class)]
class DocumentPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'documentPrices')]
    private ?Document $document = null;

    #[ORM\ManyToOne]
    private ?DocumentPriceType $priceType = null;

    #[ORM\ManyToOne]
    private ?VatLevel $vatLevel = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $vatAmount = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPriceType(): ?DocumentPriceType
    {
        return $this->priceType;
    }

    public function setPriceType(?DocumentPriceType $priceType): static
    {
        $this->priceType = $priceType;

        return $this;
    }

    public function getVatLevel(): ?VatLevel
    {
        return $this->vatLevel;
    }

    public function setVatLevel(?VatLevel $vatLevel): static
    {
        $this->vatLevel = $vatLevel;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getVatAmount(): ?string
    {
        return $this->vatAmount;
    }

    public function setVatAmount(?string $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }
}
