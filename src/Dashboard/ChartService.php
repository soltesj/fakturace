<?php

namespace App\Dashboard;

use App\Entity\Company;
use App\Repository\DocumentRepository;
use DateTime;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

class ChartService
{
    public function __construct(
        private readonly DocumentRepository $documentRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @throws Exception
     */
    public function getChart(Company $company, int $year): array
    {
        $prevYear = $year - 1;
        $dataset1 = $this->documentRepository->getChart($company,
            new DateTime("{$prevYear}-01-01"),
            new DateTime("{$prevYear}-12-31"));
        $dataset2 = $this->documentRepository->getChart($company,
            new DateTime("{$year}-01-01"),
            new DateTime("{$year}-12-31"));
        for ($n = 1; $n <= 12; $n++) {
            $labels[] = $this->translator->trans($n, domain: 'months');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $prevYear,
                    'data' => $dataset1['data'],
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => '#9BD0F5',
                ],
                [
                    'label' => $year,
                    'data' => $dataset2['data'],
                    'borderColor' => '#FF6384',
                    'backgroundColor' => '#FFB1C1',
                ],
            ],
        ];
    }
}