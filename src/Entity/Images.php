<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name: 'images')]
#[ORM\Entity]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private ?int $companyId = null;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, nullable: false)]
    private ?string $type = null;

    #[ORM\Column(name: 'url', type: Types::STRING, length: 255, nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'x', type: Types::INTEGER, nullable: true)]
    private ?int $x = null;

    #[ORM\Column(name: 'y', type: Types::INTEGER, nullable: false)]
    private ?int $y = null;

    #[ORM\Column(name: 'w', type: Types::INTEGER, nullable: false)]
    private ?int $w = null;

    #[ORM\Column(name: 'h', type: Types::INTEGER, nullable: false)]
    private ?int $h;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): void
    {
        $this->x = $x;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): void
    {
        $this->y = $y;
    }

    public function getW(): ?int
    {
        return $this->w;
    }

    public function setW(?int $w): void
    {
        $this->w = $w;
    }

    public function getH(): ?int
    {
        return $this->h;
    }

    public function setH(?int $h): void
    {
        $this->h = $h;
    }
}
