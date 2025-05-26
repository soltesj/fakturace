<?php

namespace App\BankAccount\EmailParser;

use App\BankAccount\ParsedNotificationDto;
use App\Enum\NotificationType;
use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;

class AirBankParser implements ParserInterface
{
    public function supports(string $from, string $subject, string $body): bool
    {
        return $from === 'info@airbank.cz';
    }

    public function parse(string $subject, string $body): ParsedNotificationDto
    {
        if (str_contains($subject, 'Váš dostupný zůstatek na účtu')) {
            return $this->parseBalance($body);
        }
        throw new RuntimeException('message.not_implemented');
//        if (str_contains($subject, 'vydaj na konte')) {
//            return $this->parseOutgoingTransaction($body);
//        }
//        throw new InvalidArgumentException('message.no_parsable_subject');
    }

    private function parseBalance(string $body): ParsedNotificationDto
    {
        $pattern = '/ke dni\s+(\d{2}\.\d{2}\.\d{4})\s+v\s+(\d{2}:\d{2}).*?číslo\s+(\d+\/\d+).*?je\s+([\d,]+)\s+([A-Z]{3})/u';
        if (preg_match($pattern, $body, $m)) {
            $date = DateTimeImmutable::createFromFormat('d.m.Y H:i',
                "{$m[1]} {$m[2]}") ? DateTimeImmutable::createFromFormat('d.m.Y H:i', "{$m[1]} {$m[2]}") : null;

            return new ParsedNotificationDto(
                type: NotificationType::BALANCE,
                account: str_replace('/', '', $m[3]), // můžeš to upravit podle potřeby
                balance: (float)str_replace(',', '.', $m[4]),
                date: $date,
            );
        }
        throw new InvalidArgumentException('message.no_parsable_body');
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
}