<?php

namespace App\Doctrine;

use App\Entity\Document;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DocumentSearch extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if ($targetEntity->getReflectionClass()->name !== Document::class) {
            return '';
        }

        return sprintf('%s.tag like %s', $targetTableAlias, $this->getParameter('query'));
    }
}