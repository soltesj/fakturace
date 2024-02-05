<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Numbers
 */
#[ORM\Table(name: 'numbers')]
#[ORM\Entity]
class Numbers
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    /**
     * @var int
     */
    #[ORM\Column(name: 'company_id', type: Types::INTEGER, nullable: false)]
    private int $companyId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'document_type_id', type: Types::STRING, length: 3, nullable: true)]
    private ?string $documentTypeId = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(name: 'year', type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $year = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'number_format', type: Types::STRING, length: 15, nullable: true)]
    private ?string $numberFormat = null;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'next_number', type: Types::INTEGER, nullable: true)]
    private ?int $nextNumber = null;
}
