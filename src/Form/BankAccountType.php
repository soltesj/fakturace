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
        ])->add(child: 'shortName', options: [
            'label' => 'Short name',
            'attr' => [
                'placeholder' => 'Short name',
            ],
        ])->add(child: 'accountNumber', options: [
                'label' => 'Account Number',
                'attr' => [
                    'placeholder' => 'Account Number',
                ],
        ])->add(child: 'bankCode', options: [
                'label' => 'Bank Code',
                'attr' => [
                    'placeholder' => 'Bank Code',
                ],
        ])->add(child: 'iban', options: [
                'label' => 'IBAN',
                'attr' => [
                    'placeholder' => 'IBAN',
                ],
        ])->add(child: 'bic', options: [
                'label' => 'SWIFT',
                'attr' => [
                    'placeholder' => 'SWIFT',
                ],
        ])->add(child: 'token', options: [
                'label' => 'FIO Token',
                'attr' => [
                    'placeholder' => 'FIO Token',
                ],
        ])->add(child: 'routingNumber', options: [
                'label' => 'Routing Number',
                'attr' => [
                    'placeholder' => 'Routing Number',
                ],
        ])->add(child: 'sequence', options: [
                'label' => 'Sequence',
                'attr' => [
                    'placeholder' => 'Sequence',
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
