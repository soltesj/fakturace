<?php

namespace App\Controller;

use App\Document\DocumentFactory;
use App\Document\DocumentFilterFormService;
use App\Document\DocumentNewSaver;
use App\Document\DocumentUpdater;
use App\Document\PdfService;
use App\Document\Types;
use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\DocumentFormType;
use App\Repository\DocumentRepository;
use App\Service\Date;
use App\Service\DocumentService;
use App\Service\VatService;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[IsGranted('ROLE_USER')]
class DocumentController extends AbstractController
{

    public function __construct(
        private readonly DocumentNumberGenerator $documentNumber,
        private readonly LoggerInterface $logger,
        private readonly VatService $vatService,
        private readonly DocumentFactory $documentFactory,
        private readonly DocumentService $documentService,
        private readonly DocumentRepository $documentRepository,
        private readonly DocumentUpdater $documentUpdater,
        private readonly DocumentNewSaver $documentNewSaver,
    ) {
    }

    #[Route('/{_locale}/{company}/document', name: 'app_document_index', methods: ['GET'])]
    public function index(
        Request $request,
        Company $company,
        DocumentFilterFormService $filterFormService,
    ): Response {
        $documents = [];
        $dateFrom = Date::firstDayOfJanuary();
        $dateTo = null;
        $customer = null;
        $query = null;
        $state = null;
        $formFilter = $filterFormService->createForm($company);
        $formFilter->handleRequest($request);
        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            /**
             * @var string|null $query
             * @var DateTime $dateFrom
             * @var DateTime $dateTo
             * @var Customer|null $customer
             * @var string|null $state
             * */
            list($query, $dateFrom, $dateTo, $customer, $state) = $filterFormService->handleFrom(
                $formFilter->getData(),
                $dateFrom
            );
        }
        try {
            $documents = $this->documentService->getDocumentToPay(
                $company,
                Types::INVOICE_OUTGOING_TYPES,
                $dateFrom,
                $dateTo,
                $query,
                $customer,
                $state
            );
        } catch (Exception|DBALException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->addFlash('danger', "DOCUMENT_LOADING_ERROR");
        }
        if ($request->get('isAjax') === 'true') {
            return $this->render('document/_list.html.twig', [
                'documents' => $documents,
                'company' => $company,
            ]);
        }
        $documentNumberExist = $this->documentNumber->exist(
            $company,
            Types::INVOICE_OUTGOING_TYPES,
            (int)(new DateTime)->format('Y')
        );
        if (!$documentNumberExist) {
            $this->addFlash('danger', 'NO_DOCUMENT_NUMBER_EXIST');
        }

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'company' => $company,
            'formFilter' => $formFilter->createView(),
            'documentNumberExist' => $documentNumberExist,
        ]);
    }

    #[Route('/{_locale}/{company}/document/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $vats = $this->vatService->getValidVatsByCompany($company);
        $document = $this->documentFactory->createInvoiceOutgoing($company, $user);
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentNewSaver->save($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute(
                    'app_document_index',
                    ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{_locale}/{company}/document/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Company $company,
        int $id,
    ): Response {
        $document = $this->documentRepository->findByCompanyAndId($company, $id);
        $vats = $this->vatService->getValidVatsByCompany($company);
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentUpdater->update($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute(
                    'app_document_index',
                    ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'company' => $company,
            'vats' => $vats,
        ]);
    }


    #[Route('/{_locale}/{company}/document/{id}/pdf', name: 'app_document_print', methods: ['GET'])]
    public function toPdf(
        Company $company,
        int $id,
        PdfService $pdfService,
    ): Response {
        $document = $this->documentRepository->findByCompanyAndId($company, $id);
        /** @var User $user */
        $user = $this->getUser();
        $userName = "{$user->getName()} {$user->getSurname()}";
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition',
            sprintf('attachment; filename="%s.pdf"', $document->getDocumentNumber()));
        $pdf = $pdfService->generateDocumentPdf($document, $userName);
        $response->setContent($pdf->Output('', 'S'));

        return $response;
    }
}