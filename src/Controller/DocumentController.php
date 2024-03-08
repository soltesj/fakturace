<?php

namespace App\Controller;

use App\Document\DocumentFilterFormService;
use App\Document\DocumentManager;
use App\Document\Types;
use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Entity\User;
use App\Form\DocumentFormType;
use App\Repository\DocumentRepository;
use App\Repository\DocumentTypeRepository;
use App\Repository\VatLevelRepository;
use App\Service\CompanyTrait;
use App\Service\Date;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[IsGranted('ROLE_USER')]
class DocumentController extends AbstractController
{
    use CompanyTrait;

    public function __construct(
        private readonly VatLevelRepository $vatLevelRepository,
        private readonly DocumentManager $documentManager,
        private readonly DocumentNumberGenerator $documentNumber,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/{_locale}/{company}/document/', name: 'app_document_index', methods: ['GET'])]
    public function index(
        Request $request,
        Company $company,
        DocumentRepository $documentRepository,
        DocumentFilterFormService $filterFormService,
    ): Response {
        $documents = [];
        $dateFrom = Date::firstDayOfJanuary();
        $dateTo = null;
        $customer = null;
        $query = null;
        $state = null;
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $formFilter = $filterFormService->createForm($company);
        $formFilter->handleRequest($request);
        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            list($query, $dateFrom, $dateTo, $customer, $state) = $filterFormService->handleFrom($formFilter->getData(),
                $dateFrom);
        }
        try {
            $documents = $documentRepository->list(
                $company,
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
        if ($request->get('isAjax')) {
            return $this->render('document/_list.html.twig', [
                'documents' => $documents,
                'company' => $company,
            ]);
        }
        $documentNumberExist = $this->documentNumber->exist($company, Types::INVOICE_OUTGOING_TYPES,
            (int)(new DateTime)->format('Y'));
        if (!$documentNumberExist) {
            $this->addFlash('danger', 'NO_DOCUMENT_NUMBER_EXIST');
        }

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'company' => $company,
            'formFilter' => $formFilter->createView(),
            'documentNumberExist'=> $documentNumberExist,
        ]);
    }

    #[Route('/{_locale}/{company}/document/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Company $company, DocumentTypeRepository $documentTypeRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $vats = $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
        $documentType = $documentTypeRepository->find(Types::INVOICE_OUTGOING);
        $documentNumberPlaceholder = $this->documentNumber->generate($company, $documentType,
            (new DateTime)->format('Y'));
        $documentItem = new DocumentItem();
        $document = new Document($company);
        $document->setDocumentType($documentType);
        $document->setDateIssue(new DateTime());
        $document->setDateTaxable(new DateTime());
        $document->setDateDue(new DateTime('+14 days'));
        $document->setDocumentNumber($documentNumberPlaceholder);
        $document->addDocumentItem($documentItem);
        $document->setUser($user);
        $document->setDescription('Fakturujeme Vám služby dle Vaší objednávky:');
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentManager->saveNew($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute('app_document_index', ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER);
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
        Document $document,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $vats = $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentManager->save($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute('app_document_index', ['company' => $company->getId()],
                    Response::HTTP_SEE_OTHER);
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }

            return $this->redirectToRoute('app_document_index', ['company' => $company->getId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{_locale}/{company}/document/{id}', name: 'app_document_delete', methods: ['DELETE'])]
    public function delete(
        Request $request,
        Company $company,
        Document $document,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
    }
}