<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\DocumentNumbers;
use App\Entity\DocumentType;
use App\Form\DocumentNumbersType;
use App\Repository\DocumentNumbersRepository;
use App\Repository\DocumentTypeRepository;
use App\Service\Date;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DocumentNumbersController extends AbstractController
{
    #[Route('/{_locale}/{company}/document-numbers/', name: 'app_document_numbers_index', methods: ['GET'])]
    public function index(
        Company $company,
        DocumentNumbersRepository $documentNumbersRepository,
        DocumentTypeRepository $documentTypeRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $documentTypes = $documentTypeRepository->findAll();
        $documentNumbers = [];
        $year = (int)Date::firstDayOfJanuary()->format('Y');
        foreach ($documentTypes as $documentType) {
            try {
                $documentNumber = $documentNumbersRepository->findOneByCompanyDocumentTypeYear(
                    $company,
                    $documentType,
                    $year);
            } catch (NoResultException $e) {
                try {
                    $documentNumber = clone $documentNumbersRepository->findOneByCompanyDocumentTypeYear(
                        $company,
                        $documentType,
                        $year - 1);
                    $documentNumber->setYear($year);
                    $entityManager->persist($documentNumber);
                    $entityManager->flush();
                } catch (NoResultException $e) {
                    $documentNumber = new DocumentNumbers($company, $documentType, $year, $documentType->getDefaultFormat());
                    $entityManager->persist($documentNumber);
                    $entityManager->flush();
                }
            }
            $documentNumbers[$documentType->getId()] = $documentNumber;
        }

        return $this->render('document_numbers/index.html.twig', [
            'documentNumbers' => $documentNumbers,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/document-numbers/{documentType}/new', name: 'app_document_numbers_new', methods: [
        'GET',
        'POST',
    ])]
    public function new(
        Request $request,
        Company $company,
        DocumentType $documentType,
        EntityManagerInterface $entityManager
    ): Response {
        $documentNumber = new DocumentNumbers($company, $documentType, (int)Date::firstDayOfJanuary()->format('Y'),$documentType->getDefaultFormat());
        $form = $this->createForm(DocumentNumbersType::class, $documentNumber);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($documentNumber);
            $entityManager->flush();

            return $this->redirectToRoute('app_document_numbers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document_numbers/new.html.twig', [
            'document_number' => $documentNumber,
            'company' => $company,
            'documentType' => $documentType,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{company}/document-numbers/{id}/edit', name: 'app_document_numbers_edit', methods: [
        'GET',
        'POST',
    ])]
    public function edit(
        Request $request,
        Company $company,
        DocumentNumbers $documentNumber,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(DocumentNumbersType::class, $documentNumber);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_document_numbers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document_numbers/edit.html.twig', [
            'document_number' => $documentNumber,
            'company' => $company,
            'documentType' => $documentNumber->getDocumentType(),
            'form' => $form,
        ]);
    }
}
