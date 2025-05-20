<?php

namespace App\Form;

use App\Entity\BankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(child: 'name', options: [
            'label' => t('bank_account.name'),
            'attr' => [
                'placeholder' => t('bank_account.name'),
            ],
        ])->add(child: 'shortName', options: [
            'label' => t('bank_account.name_short'),
            'attr' => [
                'placeholder' => t('bank_account.name_short'),
            ],
        ])->add(child: 'accountNumber', options: [
            'label' => t('bank_account.number'),
                'attr' => [
                    'placeholder' => t('bank_account.number'),
                ],
        ])->add(child: 'bankCode', options: [
            'label' => t('bank_account.code'),
                'attr' => [
                    'placeholder' => t('bank_account.code'),
                ],
        ])->add(child: 'iban', options: [
            'label' => t('bank_account.iban'),
                'attr' => [
                    'placeholder' => t('bank_account.iban'),
                ],
        ])->add(child: 'bic', options: [
            'label' => t('bank_account.swift'),
                'attr' => [
                    'placeholder' => t('bank_account.swift'),
                ],
        ])->add(child: 'token', options: [
            'label' => t('bank_account.fio_api_token'),
                'attr' => [
                    'placeholder' => t('bank_account.fio_api_token'),
                ],
        ])->add(child: 'routingNumber', options: [
            'label' => t('bank_account.routing_number'),
                'attr' => [
                    'placeholder' => t('bank_account.routing_number'),
                ],
        ])->add(child: 'sequence', options: [
            'label' => t('bank_account.sequence'),
                'attr' => [
                    'placeholder' => t('bank_account.sequence'),
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
