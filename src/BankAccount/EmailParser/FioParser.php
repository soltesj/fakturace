<?php

namespace App\BankAccount\EmailParser;

use App\BankAccount\ParsedNotificationDto;
use App\Enum\NotificationType;
use Exception;

class FioParser implements ParserInterface
{
    public function supports(string $from, string $subject, string $body): bool
    {
        return str_starts_with($subject, 'Fio banka');
    }

    public function parse(string $subject, string $body): ParsedNotificationDto
    {
        if (str_contains($subject, 'zustatek konta')) {
            return $this->parseBalance($body);
        }
        if (str_contains($subject, 'vydaj na konte')) {
            return $this->parseOutgoingTransaction($body);
        }
        if (str_contains($subject, 'prijem na konte')) {
            return $this->parseIngoingTransaction($body);
        }
        throw new Exception('message.no_parsable_subject');
    }

    private function parseBalance(string $body): ParsedNotificationDto
    {
        if (preg_match('/Zustatek na ucte\s+(\d+):\s+([\d,]+)/', $body, $m)) {
            return new ParsedNotificationDto(
                type: NotificationType::BALANCE,
                account: $m[1],
                balance: (float)str_replace(',', '.', $m[2])
            );
        }
        throw new Exception('message.no_parsable_body');
    }

    private function parseOutgoingTransaction(string $body): ParsedNotificationDto
    {
        return new ParsedNotificationDto(
            type: NotificationType::TRANSACTION_OUTCOMING,
            account: $this->match('/Výdaj na kontě:\s*(\d+)/', $body),
            balance: $this->matchFloat('/Aktuální zůstatek:\s*([\d,]+)/', $body),
            amount: $this->matchFloat('/Částka:\s*([\d,]+)/', $body),
            vs: $this->match('/VS:\s*(\d+)/', $body),
            us: $this->match('/US:\s*(\S+)/', $body),
            ss: $this->match('/SS:\s*(\S+)/', $body),
            ks: $this->match('/KS:\s*(\S+)/', $body),
            counterparty: $this->match('/Protiúčet:\s*(.+)/', $body),
        );
    }

    private function match(string $pattern, string $text): ?string
    {
        return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
    }

    private function matchFloat(string $pattern, string $text): ?float
    {
        return preg_match($pattern, $text, $m)
            ? (float)str_replace(',', '.', trim($m[1])) : null;
    }

    private function parseIngoingTransaction(string $body): ParsedNotificationDto
    {
        return new ParsedNotificationDto(
            type: NotificationType::TRANSACTION_OUTCOMING,
            account: $this->match('/Příjem na kontě:\s*(\d+)/', $body),
            balance: $this->matchFloat('/Aktuální zůstatek:\s*([\d,]+)/', $body),
            amount: $this->matchFloat('/Částka:\s*([\d,]+)/', $body),
            vs: $this->match('/VS:\s*(\d+)/', $body),
            us: $this->match('/US:\s*(\S+)/', $body),
            ss: $this->match('/SS:\s*(\S+)/', $body),
            ks: $this->match('/KS:\s*(\S+)/', $body),
            counterparty: $this->match('/Protiúčet:\s*(.+)/', $body),
        );
    }
}