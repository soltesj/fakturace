<?php

namespace App\EventSubscriber\Doctrine;

use App\Entity\Payment;
use App\Enum\PaymentType;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Psr\Log\LoggerInterface;

class PaymentSubscriber implements EventSubscriber
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::onFlush];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $this->logger->info('onFlush');
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $documentsToUpdate = [];
        // Collect documents that need updates
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Payment || $entity->getDocument() === null) {
                continue;
            }
            $document = $entity->getDocument();
            $documentId = spl_object_id($document);
            if (!isset($documentsToUpdate[$documentId])) {
                $documentsToUpdate[$documentId] = [
                    'document' => $document,
                    'payments' => [],
                ];
            }
            $documentsToUpdate[$documentId]['payments'][] = $entity;
        }
        if (empty($documentsToUpdate)) {
            return;
        }
        try {
            // Fetch all totals in one query
            $qb = $em->createQueryBuilder();
            $qb->select('IDENTITY(p.document) as documentId, 
                    SUM(CASE WHEN p.type = :income THEN p.price ELSE -p.price END) as total')
                ->from(Payment::class, 'p')
                ->where('p.document IN (:documents)')
                ->groupBy('p.document')
                ->setParameter('income', PaymentType::INCOME)
                ->setParameter('documents', array_map(fn($item) => $item['document'], $documentsToUpdate));
            $currentTotals = array_column($qb->getQuery()->getResult(), 'total', 'documentId');
            foreach ($documentsToUpdate as $item) {
                $document = $item['document'];
                $newPayments = $item['payments'];
                $documentId = $document->getId();
                $netTotal = $currentTotals[$documentId] ?? 0.0;
                foreach ($newPayments as $p) {
                    $netTotal += ($p->getType() === PaymentType::INCOME ? 1 : -1) * $p->getPrice();
                }
                $document->setRemainingAmount($netTotal);
                $uow->propertyChanged($document, 'remainingAmount', null, $netTotal);
                $uow->scheduleExtraUpdate($document, [
                    'remainingAmount' => [null, $netTotal],
                ]);
            }
        } catch (Exception $e) {
            $this->logger->error('Failed to update document remaining amounts: '.$e->getMessage());
            throw $e;
        }
    }
}