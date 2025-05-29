<?php

namespace App;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;

class QrCodeService
{
    public function create(
        string $iban,
        string $amount,
        string $currency,
        string $variableSymbol,
        string $message = '',
        int $size = 200
    ): ResultInterface {
        $data = "SPD*1.0*ACC:$iban*AM:$amount*CC:$currency*MSG:$message*X-VS:$variableSymbol";
        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $data,
            size: $size,
        );

        return $writer->write($qrCode);
    }
}