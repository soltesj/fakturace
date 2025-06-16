<?php

namespace App\Entity;

use App\Repository\DefaultEmailTemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DefaultEmailTemplateRepository::class)]
class DefaultEmailTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Country $country;

    #[Assert\NotBlank(message: 'document.send_by_email.subject.not_blank')]
    #[Assert\NotNull(message: 'document.send_by_email.subject.not_blank')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $subject;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 2000)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
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
