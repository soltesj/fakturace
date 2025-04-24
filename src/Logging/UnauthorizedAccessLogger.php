<?php

namespace App\Logging;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UnauthorizedAccessLogger
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function logAttempt(UserInterface $user, int $companyId, Request $request): void
    {
        $this->logger->alert('Unauthorized access attempt to company', [
            'user_id' => method_exists($user, 'getId') ? $user->getId() : null,
            'company_id' => $companyId,
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
            'url' => $request->getUri(),
            'method' => $request->getMethod(),
            'referer' => $request->headers->get('Referer'),
            'timestamp' => (new DateTimeImmutable())->format(DATE_ATOM),
        ]);
    }
}