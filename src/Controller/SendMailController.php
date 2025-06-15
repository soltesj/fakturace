<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\SendEmailFormType;
use App\Message\InvoiceEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SendMailController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    #[Route('/{_locale}/{company}/send-invoice', name: 'app_send_invoice', methods: ['POST'])]
    public function lookup(Company $company, Request $request): JsonResponse
    {
        $invoiceEmail = new InvoiceEmail(1, '', '', '', '');
        $form = $this->createForm(SendEmailFormType::class, $invoiceEmail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->bus->dispatch($invoiceEmail);

                return $this->json([
                    'status' => 'ok',
                    'massage' => '',
                ]);
            } catch (ExceptionInterface $e) {
                return $this->json(['error' => $e->getMessage()], 400);
            }
        }

        return $this->json([]);
    }
}