<?php

namespace App\EventSubscriber;

use App\Company\CompanyService;
use App\Entity\User;
use App\Logging\UnauthorizedAccessLogger;
use App\Service\AuthorizationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class CompanyAuthorizationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AuthorizationService $authorizationService,
        private Security $security,
        private CompanyService $companyService,
        private UnauthorizedAccessLogger $unauthorizedAccessLogger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $companyId = $request->attributes->get('company');
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$companyId || !$user) {
            return;
        }
        $isAuthorized = $this->authorizationService->checkUserCompanyAccess($user, $companyId);
        if (!$isAuthorized) {
            $this->unauthorizedAccessLogger->logAttempt($user, $companyId, $request);
            $request->getSession()->getFlashBag()->add('danger', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            $url = $this->companyService->getCorrectCompanyUrl($request, $user);
            $response = new RedirectResponse($url);
            $event->setController(fn() => $response);
        }
    }
}
