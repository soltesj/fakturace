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
    #[ORM\Column(name: 'type', type: Types::INTEGER, nullable: false)]
    private int $type;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 64, nullable: false)]
    private string $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'abbreviation', type: Types::STRING, length: 32, nullable: false)]
    private string $abbreviation;


}
