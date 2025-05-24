<?php

namespace App\BankAccount\EmailParser;

use App\BankAccount\ParsedNotificationDto;

interface ParserInterface
{
    public function supports(string $from, string $subject, string $body): bool;

    public function parse(string $subject, string $body): ParsedNotificationDto;
}