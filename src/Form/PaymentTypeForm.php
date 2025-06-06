<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\Payment;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentTypeForm extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Payment $payment */
        $payment = $options['data'];
        $company = $payment->getCompany();
        $builder
            ->setAction($this->urlGenerator->generate('app_payment_new', ['company' => $company->getPublicId(),]))
            ->add('paymentType', HiddenType::class, [
                'data' => $payment->getType()->value,
                'mapped' => false,
            ])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('amount')
            ->add('documentId', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('bankAccount', EntityType::class, [
                'class' => BankAccount::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('bankAccount')
                        ->andWhere('bankAccount.company = :company')
                        ->setParameter('company', $company)
                        ->andWhere('bankAccount.status = :status')
                        ->setParameter('status', 1)
                        ->orderBy('bankAccount.sequence', 'ASC');
                },
                'placeholder' => 'invoice.cash',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            '',
        ]);
    }
}
