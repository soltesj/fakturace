<?php

namespace App\BankAccount;

use App\BankAccount\EmailParser\ParserInterface;

class EmailParserResolver
{
    /**
     * @param ParserInterface[] $strategies
     */
    public function __construct(private readonly iterable $strategies)
    {
    }

    public function resolve(string $from, string $subject, string $body): ?ParserInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($from, $subject, $body)) {
                return $strategy;
            }
        }

        return null;
    }
}