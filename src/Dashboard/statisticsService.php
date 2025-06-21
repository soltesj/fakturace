<?php

namespace App\Dashboard;

use App\Document\Types;
use App\Entity\Company;
use App\Entity\Document;
use App\Enum\VatPaymentMode;
use App\Repository\DocumentRepository;
use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

readonly class statisticsService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private TranslatorInterface $translator
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

    /**
     * @return  array{
     *       thisPeriod: array<int, array{currency: string, vatTotal: string}>,
     *       previousPeriod: array<int, array{currency: string, vatTotal: string}>
     *   }
     * @throws DateMalformedStringException
     */
    public function getVatToPay(Company $company): array
    {
        list($dateFrom, $dateTo) = $this->getDates($company->getVatPaymentMode());
        $thisPeriod = $this->documentRepository->getVatToPay($company, $dateFrom, $dateTo);
        list($dateFrom, $dateTo) = $this->getDates($company->getVatPaymentMode(), 'previous');
        $prevPeriod = $this->documentRepository->getVatToPay($company, $dateFrom, $dateTo);

        return ['thisPeriod' => $thisPeriod, 'previousPeriod' => $prevPeriod];
    }

    /**
     * @return DateTimeImmutable[]
     * @throws DateMalformedStringException
     */
    public function getDates(VatPaymentMode $vatPaymentMode, $period = 'now'): array
    {
        $now = new DateTimeImmutable();
        if ($period === 'previous') {
            $now = $now->modify($vatPaymentMode === VatPaymentMode::MONTHLY ? '-1 month' : '-3 months');
        }
        if ($vatPaymentMode === VatPaymentMode::MONTHLY) {
            $dateFrom = $now->modify('first day of this month')->setTime(0, 0);
            $dateTo = $dateFrom->modify('+1 month');
        } else {
            $quarter = (int)(($now->format('n') - 1) / 3);
            $dateFrom = new DateTimeImmutable(sprintf('%d-%02d-01', $now->format('Y'), $quarter * 3 + 1));
            $dateTo = $dateFrom->modify('+3 months');
        }

        return [$dateFrom, $dateTo];
    }


    /**
     * @param Company $company
     * @return Document[]
     */
    public function getOverdueInvoices(Company $company): array
    {
        return $this->documentRepository->filtered(company: $company, documentTypes: Types::INVOICE_OUTGOING_TYPES,
            state: 'OVERDUE');
    }
}