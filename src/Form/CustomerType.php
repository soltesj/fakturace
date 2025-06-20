<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'name', options: [
                'label' => t('form.customer.name'),
                'attr' => [
                    'placeholder' => t('form.customer.name.placeholder'),
                ],
            ])
            ->add(child: 'contact', options: [
                'label' => t('form.customer.contact'),
                'attr' => [
                    'placeholder' => t('form.customer.contact.placeholder'),
                ],
            ])
            ->add(child: 'street', options: [
                'label' => t('form.customer.street'),
                'attr' => [
                    'placeholder' => t('form.customer.street.placeholder'),
                ],
            ])
            ->add(child: 'houseNumber', options: [
                'label' => t('form.customer.house_number'),
                'attr' => [
                    'placeholder' => t('form.customer.house_number.placeholder'),
                ],
            ])
            ->add(child: 'town', options: [
                'label' => t('form.customer.town'),
                'attr' => [
                    'placeholder' => t('form.customer.town.placeholder'),
                ],
            ])
            ->add(child: 'zipcode', options: [
                'label' => t('form.customer.zipcode'),
                'attr' => [
                    'placeholder' => t('form.customer.zipcode.placeholder'),
                ],
            ])
            ->add(child: 'companyNumber', options: [
                'label' => t('form.customer.company_number'),
                'attr' => [
                    'placeholder' => t('form.customer.company_number.placeholder'),
                ],
            ])
            ->add(child: 'vatNumber', options: [
                'label' => t('form.customer.vat_number'),
                'attr' => [
                    'placeholder' => t('form.customer.vat_number.placeholder'),
                ],
            ])
            ->add(child: 'isVatPayer', options: [
                'label' => t('form.customer.is_vat_payer'),
                'attr' => [
                    'placeholder' => t('form.customer.is_vat_payer.placeholder'),
                ],
            ])
            ->add(child: 'phone', options: [
                'label' => t('form.customer.phone'),
                'attr' => [
                    'placeholder' => t('form.customer.phone.placeholder'),
                ],
            ])
            ->add(child: 'email', options: [
                'label' => t('form.customer.email'),
                'attr' => [
                    'placeholder' => t('form.customer.email.placeholder'),
                ],
            ])
            ->add(child: 'website', options: [
                'label' => t('form.customer.website'),
                'attr' => [
                    'placeholder' => t('form.customer.website.placeholder'),
                ],
            ])
            ->add(child: 'bankAccount', options: [
                'label' => t('form.customer.bank_account'),
                'attr' => [
                    'placeholder' => t('form.customer.bank_account.placeholder'),
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => t('form.customer.country'),
                'attr' => [
                    'placeholder' => t('form.customer.country.placeholder'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
