<?php

namespace App\Controller;

use App\Dashboard\ChartService;
use App\Document\Types;
use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Company;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashBoardController extends AbstractController
{
    public function __construct(
        private readonly ChartService $chartService,
        private readonly LoggerInterface $logger,
        private readonly DocumentNumberGenerator $documentNumber,
    ) {
    }

    #[Route('/{_locale}/{company}/dashboard', name: 'app_dash_board')]
    public function index(Company $company): Response
    {
//        $d= null;
//        //$d = new DateTimeImmutable('yesterday');
//        $date = $d?:new DateTimeImmutable();
//        dump($date);
//        $text = 'Výdaj na kontě: 2800717957';
//        //$text = 'Příjem na kontě: 2800717957';
//        $text .= '
//Částka: 7,00
//VS: 7711025806
//Zpráva příjemci: test
//Aktuální zůstatek: 702,64
//Protiúčet: 2286497014/3030
//SS: 2
//KS: 1 ';
//        $pattern = '/(Příjem na kontě:|Výdaj na kontě:)\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/Částka:\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/VS:\s*(\d*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/Zpráva příjemci:\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/Aktuální zůstatek:\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/SS:\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/KS:\s*(\S*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
//        $pattern = '/Protiúčet:\s*(.*)/';
//        preg_match($pattern, $text, $m);
//        dump($m);
        try {
            $chartData = $this->chartService->getChart($company, (int)new DateTime()->format('Y'));
        } catch (Exception $e) {
            $chartData = [];
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        $documentNumberExist = $this->documentNumber->exist(
            $company,
            Types::INVOICE_OUTGOING_TYPES,
            (int)(new DateTime)->format('Y')
        );

        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
            'chartData' => $chartData,
            'documentNumberExist' => $documentNumberExist,
        ]);
    }
}
