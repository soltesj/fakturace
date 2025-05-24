<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\CompanyInboxIdentifier;
use App\Repository\CompanyInboxRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\String\ByteString;

readonly class InboxIdentifierGenerator
{
    public function __construct(
        private EntityManagerInterface $em,
        private CompanyInboxRepository $companyInboxRepository
    ) {
    }

    public function generateForCompany(Company $company): string
    {
        $identifier = $this->generateUniqueIdentifier();
        $inbox = new CompanyInboxIdentifier();
        $inbox->setCompany($company);
        $inbox->setIdentifier($identifier);
        $this->em->persist($inbox);
        $this->em->flush();

        return $identifier;
    }

    private function generateUniqueIdentifier(int $length = 6, int $maxTries = 20): string
    {
        for ($i = 0; $i < $maxTries; $i++) {
            $identifier = ByteString::fromRandom($length)->toString();
            if (!$this->companyInboxRepository->findOneBy(['inboxIdentifier' => $identifier])) {
                return $identifier;
            }
        }
        throw new RuntimeException('Nepodařilo se vygenerovat unikátní inbox identifikátor.');
    }
}