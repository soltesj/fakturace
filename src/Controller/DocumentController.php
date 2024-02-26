<?php

namespace App\Controller;

use App\Document\DocumentManager;
use App\Document\DocumentNumber;
use App\Document\Types;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Form\DocumentFormType;
use App\Repository\CompanyRepository;
use App\Repository\DocumentNumbersRepository;
use App\Repository\DocumentRepository;
use App\Repository\VatLevelRepository;
use App\Service\CompanyTrait;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[IsGranted('ROLE_USER')]
#[Route('/document')]
class DocumentController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CompanyRepository $companyRepository,
        private VatLevelRepository $vatLevelRepository,
        private DocumentManager $documentManager,
        private DocumentNumber $documentNumber,
        private DocumentNumbersRepository $documentNumbersRepository,
        private LoggerInterface $logger

    ) {
        $this->session = $requestStack->getSession();
    }

    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        $company = $this->getCompany();
        $documents = $documentRepository->findByCompany($company, Types::INVOICE_OUTGOING_TYPES);

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'company' => $company,
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        DocumentNumber $documentNumber
    ): Response {
        $company = $this->getCompany();
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
                return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(),$e->getTrace());
            }
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        $vats = $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
        if ($document->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_customer_index');
        }
        $form = $this->createForm(DocumentFormType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->documentManager->save($document);
                $this->addFlash('success', 'INVOICE_STORED');
                return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
            } catch (Throwable $e) {
                $this->addFlash('danger', 'INVOICE_NOT_STORED');
                $this->logger->error($e->getMessage(),$e->getTrace());
            }

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
            'company' => $company,
            'vats' => $vats,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['DELETE'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        if ($document->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_customer_index');
        }
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}