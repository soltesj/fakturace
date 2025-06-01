<?php

namespace App\BankAccount;

use App\Enum\NotificationType;
use DateTimeImmutable;

class ParsedNotificationDto
{

    public function __construct(
        public NotificationType $type,
        public string $account,
        public ?float $balance = null,
        public ?float $amount = null,
        public ?string $currency = null,
        public ?string $vs = null,
        public ?string $us = null,
        public ?string $ss = null,
        public ?string $ks = null,
        public ?string $counterparty = null,
        public ?DateTimeImmutable $date = null,
        public ?string $message = null,
        public ?string $transactionId = null,
    ) {
    }
}