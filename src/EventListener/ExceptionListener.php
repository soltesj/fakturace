<?php

namespace App\EventListener;

use App\Company\AccessDeniedException;
use App\Company\NotFoundException;
use App\Company\CompanyService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener(event: ExceptionEvent::class, method: 'onKernelException')]
class ExceptionListener
{
    public function __construct(
        private CompanyService $companyService,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $user = $this->tokenStorage->getToken()?->getUser();
        if ($user && (
                $exception instanceof AccessDeniedException && $exception->getMessage() === 'You do not have access to this company.'
                || $exception instanceof NotFoundException && $exception->getMessage() === 'Company not found.'
            )) {
            $url = $this->companyService->getCorrectCompanyUrl($request, $user);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}