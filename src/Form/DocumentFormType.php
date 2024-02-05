<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\PaymentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('documentType', EntityType::class, [
            'class' => DocumentType::class,
            'choice_label' => 'name',
            'label' => 'document Type',
            'attr' => [
                'placeholder' => 'Document Type',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add('documentNumber', options: [
                'label' => 'documentNumber',
                'attr' => [
                    'placeholder' => 'documentNumber',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('variableSymbol', options: [
                'label' => 'variableSymbol',
                'attr' => [
                    'placeholder' => 'variableSymbol',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('paymentType', EntityType::class, [
                'class' => PaymentType::class,
                'choice_label' => 'name',
                'label' => 'payment Type',
                'attr' => [
                    'placeholder' => 'payment Type',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('transferTax', options: [
                'label' => 'transferTax',
                'attr' => [
                    'placeholder' => 'transferTax',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('dateIssue', options: [
                'label' => 'dateIssue',
                'attr' => [
                    'placeholder' => 'dateIssue',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('dateTaxable', options: [
                'label' => 'dateTaxable',
                'attr' => [
                    'placeholder' => 'dateTaxable',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('dateDue', options: [
                'label' => 'dateDue',
                'attr' => [
                    'placeholder' => 'dateDue',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('bankAccount', EntityType::class, [
                'class' => BankAccount::class,
                'choice_label' => 'name',
                'label' => 'bankAccount',
                'attr' => [
                    'placeholder' => 'bankAccount',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'label' => 'customer',
                'attr' => [
                    'placeholder' => 'customer',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerName', options: [
                'label' => 'customerName',
                'attr' => [
                    'placeholder' => 'customerName',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])/* ->add('customerContact', options: [
                'label' => 'customerContact',
                'attr' => [
                    'placeholder' => 'customerContact',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])*/ ->add('customerStreet', options: [
                'label' => 'customerStreet',
                'attr' => [
                    'placeholder' => 'customerStreet',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerHouseNumber', options: [
                'label' => 'customer_house_number',
                'attr' => [
                    'placeholder' => 'customer_house_number',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerTown', options: [
                'label' => 'customerTown',
                'attr' => [
                    'placeholder' => 'customerTown',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerZipcode', options: [
                'label' => 'Zipcode',
                'attr' => [
                    'placeholder' => 'Zipcode',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerIc', options: [
                'label' => 'customerIc',
                'attr' => [
                    'placeholder' => 'customerIc',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('customerDic', options: [
                'label' => 'customerDic',
                'attr' => [
                    'placeholder' => 'customerDic',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('currency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => 'currencyCode',
                'label' => 'currency',
                'attr' => [
                    'placeholder' => 'currency',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('rate', options: [
                'label' => 'currency rate',
                'attr' => [
                    'placeholder' => 'currency rate',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('tag', options: [
                'label' => 'tag',
                'attr' => [
                    'placeholder' => 'tag',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('description', options: [
                'label' => 'description',
                'attr' => [
                    'placeholder' => 'description',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('vatLow', HiddenType::class)->add('priceWithoutHighVat',
                HiddenType::class)->add('priceWithoutLowVat', HiddenType::class)->add('priceNoVat',
                HiddenType::class)->add('priceTotal', HiddenType::class)
            ->add('documentItems', CollectionType::class, [
                'entry_type' => DocumentItemFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
