<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sequence
 */
#[ORM\Table(name: 'sequence')]
#[ORM\Entity]
class Sequence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $type = 0;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: false)]
    private string $name = '';

    #[ORM\Column(type: Types::STRING, length: 32, nullable: false)]
    private string $abbreviation = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): void
    {
        $this->abbreviation = $abbreviation;
    }
}
