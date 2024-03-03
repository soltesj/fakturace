<?php

namespace App\Document;

use DateTime;
use DateTimeImmutable;

class DocumentToPay
{
    private DateTimeImmutable $dateIssue;
    private DateTimeImmutable $dateDue;

    public function __construct(
        private readonly int $id,
        private readonly string $documentNumber,
        private readonly string $customerName,
        string $dateIssue,
        string $dateDue,
        private readonly string $price,
        private readonly string $priceWithVat,
        private readonly string $toPay
    ) {
        $this->dateIssue = new DateTimeImmutable($dateIssue);
        $this->dateDue = new DateTimeImmutable($dateDue);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getDateIssue(): DateTimeImmutable
    {
        return $this->dateIssue;
    }

    public function getDateDue(): DateTimeImmutable
    {
        return $this->dateDue;
    }

    public function getPriceWithVat(): string
    {
        return $this->priceWithVat;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getToPay(): string
    {
        return $this->toPay;
    }


}