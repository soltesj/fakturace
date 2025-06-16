<?php

namespace App\Entity;

use App\Repository\EmailTemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

#[ORM\Entity(repositoryClass: EmailTemplateRepository::class)]
class EmailTemplate implements CompanyOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[Assert\NotBlank(message: 'document.send_by_email.subject.not_blank')]
    #[Assert\NotNull(message: 'document.send_by_email.subject.not_blank')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $subject;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 2000)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content;

    public function __construct(Company $company, ?string $subject = null, ?string $content = null)
    {
        $this->company = $company;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

}
