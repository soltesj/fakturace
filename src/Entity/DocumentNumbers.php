<?php

namespace App\Entity;

use App\Repository\DocumentNumbersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentNumbersRepository::class)]
class DocumentNumbers implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\ManyToOne]
    private ?DocumentType $documentType;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $year;

    #[ORM\Column(type: Types::STRING, length: 15, nullable: false)]
    private string $numberFormat;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $nextNumber = 0;

    public function __construct(Company $company, DocumentType $documentType, int $year,string $numberFormat)
    {
        $this->company = $company;
        $this->documentType = $documentType;
        $this->year = $year;
        $this->numberFormat = $numberFormat;
    }

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

    public function __clone(): void
    {
        $this->id = null;
        $this->nextNumber = 0;
    }
}
