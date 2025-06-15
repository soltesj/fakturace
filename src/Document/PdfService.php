<?php

declare(strict_types=1);

namespace App\Document;

use App\Entity\Document;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use TCPDF;
use Twig\Environment;

class PdfService
{
    public function __construct(private readonly Environment $twig) {}

    public function generateDocumentPdf(Document $document, string $author = ''): TCPDF
    {
        $language['a_meta_charset'] = 'UTF-8';
        $language['a_meta_dir'] = 'ltr';
        $language['a_meta_language'] = 'cs';
        $language['w_page'] = 'stránka';

        $qrCodeBase64 = $this->getQRcodeBase64($document);

        $pdf = new TCPDF();
        $pdf->setLanguageArray($language);
        $pdf->setFont('opensans');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($author);
        $pdf->SetTitle("{$document->getDocumentType()->getName()} {$document->getDocumentNumber()}");
        $pdf->SetSubject("{$document->getDocumentType()->getName()} {$document->getDocumentNumber()}");
        $pdf->SetKeywords("{$document->getDocumentType()->getName()} {$document->getDocumentNumber()}");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $html = $this->twig->render('document/pdf.html.twig', [
            'document' => $document,
            'qrCodeBase64' => $qrCodeBase64,
        ]);
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf;
    }

    private function getQRcodeBase64(Document $document): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200, 0),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $msg = "PLATBA FAKTURY {$document->getDocumentNumber()} QR KODEM";
        $qrCode = $writer->writeString("SPD*1.0*ACC:{$document->getBankAccount()->getIban()}*AM:{$document->getTotalAmount()}*CC:{$document->getCurrency()->getCode()}*MSG:$msg*X-VS:{$document->getVariableSymbol()}");


        return 'data:image/png;base64,' . base64_encode($qrCode);
    }
}
