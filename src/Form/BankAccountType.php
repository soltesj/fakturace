<?php

namespace App\Form;

use App\Entity\BankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(child: 'name', options: [
            'label' => 'Name',
            'attr' => [
                'placeholder' => 'Name',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add(child: 'shortName', options: [
            'label' => 'Short name',
            'attr' => [
                'placeholder' => 'Short name',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add(child: 'accountNumber', options: [
                'label' => 'Account Number',
                'attr' => [
                    'placeholder' => 'Account Number',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'bankCode', options: [
                'label' => 'Bank Code',
                'attr' => [
                    'placeholder' => 'Bank Code',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'iban', options: [
                'label' => 'IBAN',
                'attr' => [
                    'placeholder' => 'IBAN',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'bic', options: [
                'label' => 'SWIFT',
                'attr' => [
                    'placeholder' => 'SWIFT',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'token', options: [
                'label' => 'FIO Token',
                'attr' => [
                    'placeholder' => 'FIO Token',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'routingNumber', options: [
                'label' => 'Routing Number',
                'attr' => [
                    'placeholder' => 'Routing Number',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add(child: 'sequence', options: [
                'label' => 'Sequence',
                'attr' => [
                    'placeholder' => 'Sequence',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
        ]);
    }
}
