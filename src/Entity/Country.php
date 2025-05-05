<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'country')]
#[ORM\Entity]
class Country
{

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected ?int $id;

    #[ORM\Column(name: 'sname', type: Types::STRING, length: 10, nullable: false)]
    private string $sname;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $isEU;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSname(): string
    {
        return $this->sname;
    }

    public function setSname(string $sname): void
    {
        $this->sname = $sname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isEU(): bool
    {
        return $this->isEU;
    }

    public function setIsEU(bool $isEU): void
    {
        $this->isEU = $isEU;
    }
}
