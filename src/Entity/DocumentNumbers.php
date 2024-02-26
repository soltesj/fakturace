<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DocumentNumbers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    private ?Company $company = null;

    #[ORM\ManyToOne]
    private ?DocumentType $documentType = null;

    #[ORM\Column( type: Types::INTEGER, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: Types::STRING, length: 15, nullable: true)]
    private ?string $numberFormat = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $nextNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    public function getNumberFormat(): ?string
    {
        return $this->numberFormat;
    }

    public function setNumberFormat(?string $numberFormat): void
    {
        $this->numberFormat = $numberFormat;
    }

    public function getNextNumber(): ?int
    {
        return $this->nextNumber;
    }

    public function setNextNumber(?int $nextNumber): void
    {
        $this->nextNumber = $nextNumber;
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
}
