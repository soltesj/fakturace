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
        $documentTypes = Types::INVOICE_OUTGOING_TYPES;
        $builder->add('documentType', EntityType::class, [
            'class' => DocumentType::class,
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) use ($documentTypes): QueryBuilder {
                return $er->createQueryBuilder('document_type')
                    ->andWhere('document_type in (:document_type)')
                    ->setParameter('document_type',
                        $documentTypes)
                    ->orderBy('document_type.name', 'ASC');
            },
            'label' => 'document Type',
            'attr' => [
                'placeholder' => 'Document Type',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])
            ->add('documentNumber', options: [
                'label' => 'documentNumber',
                'attr' => [
                    'placeholder' => 'documentNumber',
                    'readonly' => true,
                    'class' => 'form-control-plaintext',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('variableSymbol', options: [
                'label' => 'variableSymbol',
                'attr' => [
                    'placeholder' => 'variableSymbol',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('paymentType', EntityType::class, [
                'class' => PaymentType::class,
                'choice_label' => 'name',
                'label' => 'payment Type',
                'attr' => [
                    'placeholder' => 'payment Type',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('dateIssue', options: [
                'label' => 'dateIssue',
                'attr' => [
                    'placeholder' => 'dateIssue',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('dateTaxable', options: [
                'label' => 'dateTaxable',
                'attr' => [
                    'placeholder' => 'dateTaxable',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('dateDue', options: [
                'label' => 'dateDue',
                'attr' => [
                    'placeholder' => 'dateDue',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
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
                'row_attr' => [
                    'class' => 'form-floating mb-3',
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
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
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
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('rate', options: [
                'label' => 'currency rate',
                'attr' => [
                    'placeholder' => 'currency rate',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('tag', options: [
                'label' => 'tag',
                'attr' => [
                    'placeholder' => 'tag',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('description', options: [
                'label' => 'description',
                'attr' => [
                    'placeholder' => 'description',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
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
                'row_attr' => [
                    'class' => 'form-floating mb-3',
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
