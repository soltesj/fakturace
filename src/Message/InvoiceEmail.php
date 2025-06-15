<?php

namespace App\Message;

final class InvoiceEmail
{
    public function __construct(
        public int $documentId,
        public string $to,
        public string $subject,
        public string $content,
        public string $username,
    ) {
    }
}
