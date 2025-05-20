<?php

namespace App\Form;

use App\Document\Types;
use App\Entity\BankAccount;
use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\PaymentType;
use App\Enum\VatMode;
use App\Service\VatModeService;
use App\Status\StatusValues;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Document $document */
        $document = $options['data'];
        $company = $document->getCompany();
        $builder->add('documentNumber', options: [
                'label' => 'documentNumber',
                'attr' => [
                    'placeholder' => 'documentNumber',
                    'readonly' => true,
                ],
            ])
            ->add('variableSymbol', options: [
                'label' => 'variableSymbol',
                'attr' => [
                    'placeholder' => 'variableSymbol',
                ],
            ])
            ->add('paymentType', EntityType::class, [
                'class' => PaymentType::class,
                'choice_label' => 'name',
                'label' => 'payment Type',
                'attr' => [
                    'placeholder' => 'payment Type',
                ],
            ])
            ->add('dateIssue', options: [
                'label' => 'dateIssue',
                'attr' => [
                    'placeholder' => 'dateIssue',
                ],
            ])
            ->add('dateTaxable', options: [
                'label' => 'dateTaxable',
                'attr' => [
                    'placeholder' => 'dateTaxable',
                ],
            ])
            ->add('dateDue', options: [
                'label' => 'dateDue',
                'attr' => [
                    'placeholder' => 'dateDue',
                ],
            ])
            ->add('bankAccount', EntityType::class, [
                'class' => BankAccount::class,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('bank_account')
                        ->andWhere('bank_account.company = :company')
                        ->setParameter('company', $company)
                        ->andWhere('bank_account.status = :status')
                        ->setParameter('status', StatusValues::STATUS_ACTIVE)
                        ->orderBy('bank_account.sequence', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'bankAccount',
                'attr' => [
                    'placeholder' => 'bankAccount',
                ],
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('customer')
                        ->andWhere('customer.company = :company')
                        ->setParameter('company', $company)
                        ->andWhere('customer.status = :status')
                        ->setParameter('status', StatusValues::STATUS_ACTIVE)
                        ->orderBy('customer.name', 'ASC');
                },
                'label' => 'customer',
                'placeholder' => '',
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('currency')
                        ->andWhere('currency.id in( :companyCurrencies)')
                        ->setParameter('companyCurrencies',
                            $company->getCurrency())
                        ->orderBy('currency.currencyCode', 'ASC');
                },
                'choice_label' => 'currencyCode',
                'label' => 'currency',
                'attr' => [
                    'placeholder' => 'currency',
                ],
            ])
            ->add('rate', options: [
                'label' => 'currency rate',
                'attr' => [
                    'placeholder' => 'currency rate',
                ],
            ])
            ->add('note', options: [
                'label' => 'note',
                'attr' => [
                    'placeholder' => 'note',
                ],
            ])
            ->add('description', options: [
                'label' => 'description',
                'attr' => [
                    'placeholder' => 'description',
                ],
            ])
            ->add('priceWithoutHighVat', HiddenType::class)
            ->add('priceWithoutLowVat', HiddenType::class)
            ->add('priceNoVat', HiddenType::class)
            ->add('priceTotal', HiddenType::class)
            ->add('documentItems', CollectionType::class, [
                'entry_type' => DocumentItemFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('vatMode', ChoiceType::class, [
                'choices' => VatMode::cases(),
                //$this->vatModeService->getAvailableVatModes($company,$document->getCustomer()),
                'choice_label' => fn(VatMode $mode) => $mode->label(),
                'choice_value' => fn(?VatMode $mode) => $mode?->value,
//                'data' => $this->vatModeService->getDefaultVatMode($company)->value,
                'label' => 'VAT_MODE',
                'required' => true,
                'attr' => [
                    'placeholder' => 'VAT_MODE',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'document_types',
        ]);
    }
}
