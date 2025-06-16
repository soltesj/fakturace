<?php

namespace App\EventSubscriber\Doctrine;

use App\Company\Creation\DocumentNumberInitializerOnCompanyCreation;
use App\Company\Creation\EmailTemplateInitializerOnCompanyCreation;
use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::onFlush)]
readonly class CompanyCreationSubscriber
{
    public function __construct(
        private EmailTemplateInitializerOnCompanyCreation $emailTemplateInitializer,
        private DocumentNumberInitializerOnCompanyCreation $documentNumberInitializer
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Company) {
                continue;
            }
            $this->emailTemplateInitializer->initialize($entity, $em, $uow);
            $this->documentNumberInitializer->initialize($entity, $em, $uow);
        }
    }
}