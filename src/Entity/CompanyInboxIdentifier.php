<?php

namespace App\Entity;

use App\Repository\CompanyInboxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyInboxRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_inbox_identifier', columns: ['identifier'])]
class CompanyInboxIdentifier implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private string $identifier;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'identifiers')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }
}