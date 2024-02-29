<?php

namespace App\Document;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class DocumentFilterFormService
{
    public function handleFrom(
        FormInterface $formFilter,
        EntityManagerInterface $entityManager,
    ): void {
        $data = $formFilter->getData();
        $this->handleQuery($data['q'], $entityManager);
        $this->handleDateFrom($data['dateFrom'], $entityManager);
        $this->handleDateTo($data['dateTo'], $entityManager);
    }

    public function handleQuery(?string $q, EntityManagerInterface $entityManager): void
    {
        if ($q !== null) {
            $entityManager->getFilters()
                ->enable('document_search')
                ->setParameter('query', '%'.$q.'%');
        }
    }

    public function handleDateFrom(?DateTime $dateFrom, EntityManagerInterface $entityManager): void
    {
        if ($dateFrom !== null) {
            $entityManager->getFilters()
                ->enable('document_dateFrom')
                ->setParameter('dateFrom', $dateFrom->format('Y-m-d'));
        }
    }

    public function handleDateTo(?DateTime $dateTo, EntityManagerInterface $entityManager): void
    {
        if ($dateTo !== null) {
            $entityManager->getFilters()
                ->enable('document_dateTo')
                ->setParameter('dateTo', $dateTo->format('Y-m-d'));
        }
    }
}