<?php

namespace App\MessageHandler;

use App\Document\PdfService;
use App\Message\InvoiceEmail;
use App\Repository\DocumentRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class SendInvoiceMessageHandler
{
    public function __construct(
        private DocumentRepository $repository,
        private MailerInterface $mailer,
        private PdfService $pdfService,
    ) {
    }

    public function __invoke(InvoiceEmail $message): void
    {
        $document = $this->repository->find($message->documentId);
        $company = $document->getCompany()->getName();
        $fileName = $document->getDocumentNumber().'.pdf';
        $pdf = $this->pdfService->generateDocumentPdf($document, $company)->Output('', 'S');
        $email = new Email()
            ->from('noreplay@i-fakturace.eu')
            ->to($message->to)
            ->subject($message->subject)
            ->text($message->content)
            ->attach($pdf, $fileName, 'application/pdf');
        $this->mailer->send($email);
    }
}
