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
use App\Entity\Document;
use App\Entity\Payment;
use App\Entity\User;
use App\Enum\PaymentType;
use App\Form\DocumentFormType;
use App\Form\PaymentTypeForm;
use App\Repository\DocumentRepository;
use App\Service\Date;
use App\Service\VatService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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
        private readonly DocumentUpdater $documentUpdater,
        private readonly DocumentNewSaver $documentNewSaver,
        private readonly DocumentRepository $documentRepository,
    ) {
    }

    #[Route('/{_locale}/{company}/document', name: 'app_document_index', methods: ['GET'])]
    public function index(Request $request, Company $company, DocumentFilterFormService $filterFormService): Response
    {
        $documents = [];
        $dateFrom = Date::firstDayOfJanuary();
        $dateTo = null;
        $customer = null;
        $query = null;
        $state = null;
        $payment = new Payment($company, PaymentType::INCOME, 0);
        $formPayment = $this->createForm(PaymentTypeForm::class, $payment);
        $formFilter = $filterFormService->createForm($company);
        $formFilter->handleRequest($request);
        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            /**
             * @var string|null $query
             * @var DateTime $dateFrom
             * @var DateTime|null $dateTo
             * @var Customer|null $customer
             * @var string|null $state
             * */
            [$query, $dateFrom, $dateTo, $customer, $state] = $filterFormService->handleFrom(
                $formFilter->getData(),
                $dateFrom
            );
        }
        try {
            $documents = $this->documentRepository->filtered($company,
                Types::INVOICE_OUTGOING_TYPES,
                $dateFrom,
                $dateTo,
                $query,
                $customer,
                $state);

        } catch (Exception|DBALException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->addFlash('danger', "DOCUMENT_LOADING_ERROR");
        }
        if ($request->isXmlHttpRequest()) {
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
            $this->addFlash('error', 'NO_DOCUMENT_NUMBER_EXIST');
        }

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'company' => $company,
            'formFilter' => $formFilter->createView(),
            'formPayment' => $formPayment->createView(),
            'documentNumberExist' => $documentNumberExist,
        ]);
    }

    #[Route('/{_locale}/{company}/document/{document}', name: 'app_document_show', methods: ['GET'])]
    #[IsGranted('VIEW', subject: 'document')]
    public function show(Document $document, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'company' => $company,
        ]);
    }


    #[Route('/{_locale}/{company}/document/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    #[IsGranted('CREATE')]
    public function new(Request $request, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $vatsDomestic = $this->vatService->getValidVatsByCompany($company);
        $vats['domestic'] = $vatsDomestic;
        $vats['domestic_reverse_charge'] = $vatsDomestic;
        $document = $this->documentFactory->createInvoiceOutgoing($company, $user);
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentNewSaver->save($document);
                $this->addFlash('success', 'message.invoice.stored');

                return $this->redirectToRoute(
                    'app_document_index',
                    ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Throwable $e) {
                $this->addFlash('danger', 'message.invoice.not_stored');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'company' => $company,
            'vats' => $vats,
            'vatMode' => $document->getVatMode()->value,
        ]);
    }

    #[Route('/{_locale}/{company}/document/{document}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'document')]
    public function edit(Request $request, Company $company, Document $document): Response
    {
        $originalItems = new ArrayCollection();
        foreach ($document->getDocumentItems() as $documentItem) {
            $originalItems->add($documentItem);
        }
        $vatsDomestic = $this->vatService->getValidVatsByCompany($company);
        $vats['domestic'] = $vatsDomestic;
        $vats['domestic_reverse_charge'] = $vatsDomestic;
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentUpdater->update($document, $originalItems);
                $this->addFlash('success', 'message.invoice.stored');

                return $this->redirectToRoute(
                    'app_document_index',
                    ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Throwable $e) {
                $this->addFlash('error', 'message.invoice.not_stored');
                $this->addFlash('error', $e->getMessage());
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'company' => $company,
            'vats' => $vats,
            'vatMode' => $document->getVatMode()->value,
        ]);
    }


    #[Route('/{_locale}/{company}/document/{document}/pdf', name: 'app_document_print', methods: ['GET'])]
    #[IsGranted('VIEW', subject: 'document')]
    public function toPdf(Company $company, Document $document, PdfService $pdfService): Response
    {
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