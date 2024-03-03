<?php

namespace App\Controller;

use App\Document\DocumentFilterFormService;
use App\Document\DocumentManager;
use App\Document\DocumentNumber;
use App\Document\Types;
use App\Entity\Company;
use App\Entity\Customer;
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
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
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
        DocumentFilterFormService $filterFormService,
    ): Response {
        $documents = [];
        $dateFrom = new DateTime((new DateTime())->format('Y').'-01-01');
        $dateTo = null;
        $customer = null;
        $query = null;
        $state = null;
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $formFilter = $this->getFilterForm($company);
        $formFilter->handleRequest($request);
        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            $filterFormService->handleFrom($formFilter, $entityManager);
            $data = $formFilter->getData();
            if ($data['q'] !== null) {
                $query = $data['q'];
            }
            if ($data['dateFrom'] !== null) {
                $dateFrom = $data['dateFrom'];
            }
            if ($data['dateTo'] !== null) {
                $dateTo = $data['dateTo'];
            }
            if ($data['customer'] !== null) {
                $customer = $data['customer'];
            }
            if ($data['state'] !== null) {
                $state = $data['state'];
            }
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
//            dump($documents);
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

    /**
     * @param Company $company
     * @return FormInterface
     */
    public function getFilterForm(Company $company): FormInterface
    {
        return $this->createFormBuilder()->setMethod('GET')
            ->add('q', TextType::class, [
                'label' => 'search',
                'required' => false,
                'attr' => [
                    'placeholder' => 'search',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('state', ChoiceType::class, [
                'choices' => ['NO_PAID' => 'NO_PAID', 'PAID' => 'PAID', 'ALL' => 'ALL', 'OVERDUE' => 'OVERDUE'],
                'data' => 'NO_PAID',
                'label' => 'state',
                'required' => true,
                'attr' => [
                    'placeholder' => 'state',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'label' => 'customer',
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('customer')
                        ->andWhere('customer.company = :company')
                        ->setParameter('company', $company)
                        ->orderBy('customer.name', 'ASC');
                },
                'attr' => [
                    'placeholder' => 'customer',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('dateFrom', DateType::class, [
                'label' => 'dateFrom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateFrom',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'dateTo',
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateTo',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->getForm();
    }
}