<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 */
#[ORM\Table(name: 'images')]
#[ORM\Entity]
class Images
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
     * @var string
     */
    #[ORM\Column(name: 'type', type: Types::STRING, length: 10, nullable: false)]
    private string $type;

    /**
     * @var string
     */
    #[ORM\Column(name: 'url', type: Types::STRING, length: 255, nullable: false)]
    private string $url;

    /**
     * @var float|null
     */
    #[ORM\Column(name: 'x', type: Types::FLOAT, precision: 5, scale: 2, nullable: true)]
    private ?float $x = null;

    /**
     * @var float
     */
    #[ORM\Column(name: 'y', type: Types::FLOAT, precision: 5, scale: 2, nullable: false)]
    private float $y;

    /**
     * @var float
     */
    #[ORM\Column(name: 'w', type: Types::FLOAT, precision: 5, scale: 2, nullable: false)]
    private float $w;

    /**
     * @var float
     */
    #[ORM\Column(name: 'h', type: Types::FLOAT, precision: 5, scale: 2, nullable: false)]
    private float $h;


}
