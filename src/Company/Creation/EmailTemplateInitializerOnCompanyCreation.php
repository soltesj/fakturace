<?php

namespace App\Company\Creation;

use App\Entity\Company;
use App\Entity\EmailTemplate;
use App\Repository\DefaultEmailTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

readonly class EmailTemplateInitializerOnCompanyCreation
{
    public function __construct(private DefaultEmailTemplateRepository $defaultEmailTemplateRepository)
    {
    }

    public function initialize(Company $company, EntityManagerInterface $em, UnitOfWork $uow): void
    {
        $country = $company->getCountry();
        $defaultTemplate = $this->defaultEmailTemplateRepository->findOneByCountry($country);
        if (!$defaultTemplate) {
            return;
        }
        $emailTemplate = new EmailTemplate($company);
        $emailTemplate->setSubject($defaultTemplate->getSubject());
        $emailTemplate->setContent($defaultTemplate->getContent());
        $em->persist($emailTemplate);
        $uow->computeChangeSet($em->getClassMetadata(EmailTemplate::class), $emailTemplate);
    }
}