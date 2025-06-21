<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyFormType;
use App\Form\UserFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/{_locale}/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_COMPANY_EDITOR', 'ROLE_COMPANY_ADMIN']);
        $company = new Company();
        $company->setMaturityDays(14);
        $company->addUser($user);
        $items = ['user' => $user, 'company' => $company];
        $form = $this->createFormBuilder($items)
            ->add('user', UserFormType::class)
            ->add('company', CompanyFormType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $request->request->all()['form']['user']['plainPassword'];
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            if (!$company->isVatPayer()) {
                $company->setVatPaymentMode(null);
            }
            $entityManager->persist($company);
            $entityManager->flush();
            $address = new Address('registration@i-fakturace.eu', 'registrace');
            $template = new TemplatedEmail()
                ->from($address)
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig');
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $template
            );

            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }
        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home');
    }
}
