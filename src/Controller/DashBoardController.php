<?php

namespace App\Controller;

use App\Dashboard\statisticsService;
use App\Document\Types;
use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Company;
use DateTime;
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
        private readonly statisticsService $statisticsService,
        private readonly LoggerInterface $logger,
        private readonly DocumentNumberGenerator $documentNumber,
    ) {
    }

    #[Route('/{_locale}/{company}/dashboard', name: 'app_dash_board')]
    public function index(Company $company): Response
    {
        try {
            $vatsToPay = $this->statisticsService->getVatToPay($company);
            $chartData = $this->statisticsService->getChart($company, (int)new DateTime()->format('Y'));
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
            'vatsToPay' => $vatsToPay,
        ]);
    }
}
