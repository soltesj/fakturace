<?php

namespace App\Entity;

use App\Repository\DocumentTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentTypeRepository::class)]
class DocumentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 16, nullable: false)]
    private ?string $defaultFormat = null;

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

    public function getDefaultFormat(): ?string
    {
        return $this->defaultFormat;
    }

    public function setDefaultFormat(string $defaultFormat): void
    {
        $this->defaultFormat = $defaultFormat;
    }


}
