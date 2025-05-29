<?php

namespace App\Controller;

use App\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QrCodeGeneratorController extends AbstractController
{
    public function __construct(private readonly QrCodeService $qrCodeService)
    {
    }

    #[Route('/{_locale}/qrcode', name: 'app_qrcode_generator')]
    public function index(Request $request): Response
    {
        $size = (int)$request->get('size', 200);
        $output = $request->get('output');
        $iban = $request->get('iban');
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $variableSymbol = $request->get('vs');
        $message = $request->get('message');
        $result = $this->qrCodeService->create($iban, $amount, $currency, $variableSymbol, $message, $size);

        return match ($output) {
            'download' => new Response(content: $result->getString(), headers: [
                'content-type' => $result->getMimeType(),
                'Content-Disposition' => 'attachment; filename="qr.png"',
            ]),
            'base64' => new Response(content: $result->getDataUri()),
            default => new Response(content: $result->getString(),
                headers: ['content-type' => $result->getMimeType(),]),
        };
    }
}
