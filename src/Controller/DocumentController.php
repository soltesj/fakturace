<?php

namespace App\Controller;

use App\Document\DocumentFilterFormService;
use App\Document\DocumentManager;
use App\Document\DocumentNumber;
use App\Document\Types;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Entity\User;
use App\Form\DocumentFilterType;
use App\Form\DocumentFormType;
use App\Repository\CompanyRepository;
use App\Repository\DocumentNumbersRepository;
use App\Repository\DocumentRepository;
use App\Repository\VatLevelRepository;
use App\Service\CompanyTrait;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[IsGranted('ROLE_USER')]
class DocumentController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly VatLevelRepository $vatLevelRepository,
        private readonly DocumentManager $documentManager,
        private readonly DocumentNumber $documentNumber,
        private readonly DocumentNumbersRepository $documentNumbersRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/{company}/document/', name: 'app_document_index', methods: ['GET'])]
    public function index(
        Request $request,
        Company $company,
        DocumentRepository $documentRepository,
        EntityManagerInterface $entityManager,
        DocumentFilterFormService $filterFormService
    ): Response {
        $documents = [];
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $formFilter = $this->createForm(DocumentFilterType::class);
        $formFilter->handleRequest($request);
        if ($formFilter->isSubmitted()) {
            $filterFormService->handleFrom($formFilter, $entityManager);
        } else {
            $dateFrom = (new DateTime())->format('Y').'-01-01';
            $entityManager->getFilters()
                ->enable('document_dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }
        try {
            $documents = $documentRepository->findByCompany($company, Types::INVOICE_OUTGOING_TYPES);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->addFlash('danger', "DOCUMENT_LOADING_ERROR");
        }

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'company' => $company,
            'formFilter' => $formFilter,
        ]);
    }

    #[Route('/{company}/document/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Company $company, DocumentNumber $documentNumber): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $vats = $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
        $documentNumberPlaceholder = $documentNumber->generate($company, Types::INVOICE_OUTGOING,
            (new DateTime)->format('Y'));
        $documentItem = new DocumentItem();
        $document = new Document($company);
        $document->setDateIssue(new DateTime());
        $document->setDateTaxable(new DateTime());
        $document->setDateDue(new DateTime('+14 days'));
        $document->setDocumentNumber($documentNumberPlaceholder);
        $document->addDocumentItem($documentItem);
        $document->setUser($this->getUser());
        $document->setDescription('Fakturujeme Vám služby dle Vaší objednávky:');
        //['document_types' => Types::INVOICE_OUTGOING_TYPES]
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentManager->saveNew($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute('app_document_index', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{company}/document/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Company $company,
        Document $document,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $vats = $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentManager->save($document);
                $this->addFlash('success', 'INVOICE_STORED');

                return $this->redirectToRoute('app_document_index', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }

            return $this->redirectToRoute('app_document_index', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{company}/document/{id}', name: 'app_document_delete', methods: ['DELETE'])]
    public function delete(
        Request $request,
        Company $company,
        Document $document,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
    }
}