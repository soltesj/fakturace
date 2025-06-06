<?php

namespace App\Controller;

use App\Document\DocumentFilterFormService;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\Payment;
use App\Entity\User;
use App\Enum\PaymentType;
use App\Form\PaymentTypeForm;
use App\Payment\PaymentService;
use App\Repository\PaymentRepository;
use App\Service\Date;
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
class PaymentController extends AbstractController
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentService $paymentService,
    ) {
    }

    #[Route('/{_locale}/{company}/payment', name: 'app_payment_index', methods: ['GET'])]
    public function index(Request $request, Company $company, DocumentFilterFormService $filterFormService): Response
    {
        $payments = [];
        $dateFrom = Date::firstDayOfJanuary();
        $dateTo = null;
        $bankAccount = null;
        $paymentType = null;//PaymentType::INCOME;
        $state = null;
//        $formFilter = $filterFormService->createForm($company);
//        $formFilter->handleRequest($request);
//        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
//            /**
//             * @var string|null $query
//             * @var DateTime $dateFrom
//             * @var DateTime|null $dateTo
//             * @var Customer|null $customer
//             * @var string|null $state
//             * */
//            [$query, $dateFrom, $dateTo, $customer, $state] = $filterFormService->handleFrom(
//                $formFilter->getData(),
//                $dateFrom
//            );
//        }
        try {
            $payments = $this->paymentRepository->filtered(
                $company,
                $paymentType,
                $dateFrom,
                $dateTo,
                $bankAccount,
            );
        } catch (Exception|DBALException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->addFlash('danger', "DOCUMENT_LOADING_ERROR");
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render('payment/_list.html.twig', [
                'payments' => $payments,
                'company' => $company,
            ]);
        }
//        $documentNumberExist = $this->documentNumber->exist(
//            $company,
//            Types::INVOICE_OUTGOING_TYPES,
//            (int)(new DateTime)->format('Y')
//        );
//        if (!$documentNumberExist) {
//            $this->addFlash('error', 'NO_DOCUMENT_NUMBER_EXIST');
//        }
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'company' => $company,
//            'formFilter' => $formFilter->createView(),
//            'documentNumberExist' => $documentNumberExist,
        ]);
    }

    #[Route('/{_locale}/{company}/payment/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    #[IsGranted('CREATE')]
    public function new(Request $request, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $payment = new Payment($company, PaymentType::INCOME, 0);
        $form = $this->createForm(PaymentTypeForm::class, $payment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->paymentService->processForm($form, $payment);
                if ($request->isXmlHttpRequest()) {
                    return $this->json(['paymentId' => $payment->getId()]);
                }
                $this->addFlash('success', 'message.invoice.stored');

                return $this->redirectToRoute(
                    'app_payment_index',
                    ['company' => $company->getPublicId()],
                    Response::HTTP_SEE_OTHER
                );
            } catch (Throwable $e) {
                if ($request->isXmlHttpRequest()) {
                    return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
                $this->addFlash('danger', 'message.invoice.not_stored');
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }

        return $this->render('payment/new.html.twig', [
            'document' => $payment,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

//    #[
//        Route('/{_locale}/{company}/payment/{payment}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
//    #[IsGranted('EDIT', subject: 'payment')]
//    public function edit(Request $request, Company $company, Document $document): Response
//    {
//        $originalItems = new ArrayCollection();
//        foreach ($document->getDocumentItems() as $documentItem) {
//            $originalItems->add($documentItem);
//        }
//        $vatsDomestic = $this->vatService->getValidVatsByCompany($company);
//        $vats['domestic'] = $vatsDomestic;
//        $vats['domestic_reverse_charge'] = $vatsDomestic;
//        $form = $this->createForm(DocumentFormType::class, $document);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            try {
//                $this->documentUpdater->update($document, $originalItems);
//                $this->addFlash('success', 'message.invoice.stored');
//
//                return $this->redirectToRoute(
//                    'app_payment_index',
//                    ['company' => $company->getPublicId()],
//                    Response::HTTP_SEE_OTHER
//                );
//            } catch (Throwable $e) {
//                $this->addFlash('error', 'message.invoice.not_stored');
//                $this->addFlash('error', $e->getMessage());
//                $this->logger->error($e->getMessage(), $e->getTrace());
//            }
//        }
//
//        return $this->render('document/edit.html.twig', [
//            'document' => $document,
//            'form' => $form->createView(),
//            'company' => $company,
//            'vats' => $vats,
//            'vatMode' => $document->getVatMode()->value,
//        ]);
//    }


}