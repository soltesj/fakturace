<?php

namespace App\Payment;

use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class PaymentService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function processForm(FormInterface $form, Payment $payment): void
    {
        $paymentType = $form->get('paymentType')->getData();
        $documentId = $form->get('documentId')->getData();
        if ($documentId !== null) {
            $document = $this->documentRepository->findOneById($documentId);
            $payment->setDocument($document);
        }
        $payment->setType(PaymentType::INCOME->value === $paymentType ? PaymentType::INCOME : PaymentType::EXPENSE);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }
}