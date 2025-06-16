<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\EmailTemplate;
use App\Form\EmailTemplateForm;
use App\Repository\EmailTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class EmailTemplateController extends AbstractController
{
    public function __construct(
        private readonly EmailTemplateRepository $emailTemplateRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/{_locale}/{company}/email-template', name: 'app_email_template')]
//    #[IsGranted('EDIT', subject: 'email-template')]
    public function index(
        Request $request,
        Company $company
    ): Response {
        $emailTemplate = $this->emailTemplateRepository->findOneByCompany($company);
        if ($emailTemplate === null) {
            $emailTemplate = new EmailTemplate($company);
        }
        $form = $this->createForm(EmailTemplateForm::class, $emailTemplate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($emailTemplate);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_email_template', ['company' => $company->getPublicId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_template/index.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
