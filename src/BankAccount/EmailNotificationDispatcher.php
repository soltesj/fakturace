<?php

namespace App\BankAccount;

use App\BankAccount\EmailParser\ParserInterface;
use InvalidArgumentException;

readonly class EmailNotificationDispatcher
{
    public function __construct(private EmailParserResolver $emailParserResolver)
    {
    }

    public function dispatch(string $from, string $subject, string $body): ParsedNotificationDto
    {
        $resolver = $this->emailParserResolver->resolve($from, $subject, $body);
        if (!$resolver instanceof ParserInterface) {
            throw new InvalidArgumentException("message.parser_not_found");
        }

        return $resolver->parse($subject, $body);
    }
}