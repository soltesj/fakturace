<?php

namespace App\EventSubscriber\Doctrine;

use App\Document\DocumentPaymentCalculator;
use App\Entity\Document;
use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Exception;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::onFlush)]
readonly class PaymentSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private PaymentRepository $paymentRepository,
        private DocumentPaymentCalculator $calculator,
    ) {
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        $documentsToUpdate = $this->collectNewPaymentsGroupedByDocument($uow);
        if (empty($documentsToUpdate)) {
            return;
        }
        try {
            $currentTotals = $this->paymentRepository->findCurrentTotalsForDocuments(
                array_map(fn($item) => $item['document'], $documentsToUpdate),
                PaymentType::INCOME->value);
            $this->updateDocuments($uow, $documentsToUpdate, $currentTotals);
        } catch (Exception $e) {
            $this->logger->error('Failed to update document remaining amounts: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @return array<int, array{document: Document, payments: Payment[]}>
     */
    private function collectNewPaymentsGroupedByDocument(UnitOfWork $uow): array
    {
        $documentsToUpdate = [];
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Payment || $entity->getDocument() === null) {
                continue;
            }
            $document = $entity->getDocument();
            $documentId = spl_object_id($document);
            $documentsToUpdate[$documentId]['document'] ??= $document;
            $documentsToUpdate[$documentId]['payments'][] = $entity;
        }

        return $documentsToUpdate;
    }

    /**
     * @param UnitOfWork $uow
     * @param array<int, array{document: Document, payments: Payment[]}> $documentsToUpdate
     * @param array<int, float> $currentTotals
     */
    private function updateDocuments(UnitOfWork $uow, array $documentsToUpdate, array $currentTotals): void
    {
        foreach ($documentsToUpdate as $item) {
            $document = $item['document'];
            /** @var Payment[] $newPayments */
            $newPayments = $item['payments'];
            $documentId = $document->getId();
            $netTotal = $this->calculator->calculateNetTotal($currentTotals[$documentId] ?? 0.0, $newPayments);
            $remainingAmount = strval($document->getTotalAmount() - $netTotal);
            $document->setRemainingAmount($remainingAmount);
            $uow->propertyChanged($document, 'remainingAmount', null, $remainingAmount);
            $uow->scheduleExtraUpdate($document, [
                'remainingAmount' => [null, $remainingAmount],
            ]);
        }
    }
}