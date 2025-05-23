<?php

namespace App\Entity;

use App\Repository\CompanyInboxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyInboxRepository::class)]
#[ORM\Table(name: 'company_inboxes')]
#[ORM\UniqueConstraint(name: 'uniq_inbox_identifier', columns: ['inbox_identifier'])]
class CompanyInbox
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private string $inboxIdentifier;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'companyInbox')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInboxIdentifier(): string
    {
        return $this->inboxIdentifier;
    }

    public function setInboxIdentifier(string $inboxIdentifier): void
    {
        $this->inboxIdentifier = $inboxIdentifier;
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