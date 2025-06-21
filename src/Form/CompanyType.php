<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Enum\VatPaymentMode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'isVatPayer', options: [
                'label' => t('form.company.is_vat_payer'),
                'attr' => [
                    'placeholder' => t('form.company.is_vat_payer'),
                ],
            ])
            ->add('vatPaymentMode', EnumType::class, [
                'class' => VatPaymentMode::class,
                'label' => t('form.company.vat_payment_mode'),
                'choice_label' => fn(VatPaymentMode $mode) => $mode->label(),
            ])
            ->add(child: 'isOss', options: [
                'label' => t('form.company.is_oss'),
                'attr' => [
                    'placeholder' => t('form.company.is_oss'),
                ],
            ])
            ->add(child: 'name', options: [
                'label' => t('form.company.name'),
                'attr' => [
                    'placeholder' => t('form.company.name'),
                ],
            ])
            ->add(child: 'contact', options: [
                'label' => t('form.company.contact'),
                'attr' => [
                    'placeholder' => t('form.company.contact'),
                ],
            ])
            ->add(child: 'street', options: [
                'label' => t('form.company.street'),
                'attr' => [
                    'placeholder' => t('form.company.street'),
                ],
            ])
            ->add(child: 'buildingNumber', options: [
                'label' => t('form.company.building_number'),
                'attr' => [
                    'placeholder' => t('form.company.building_number'),
                ],
            ])
            ->add(child: 'city', options: [
                'label' => t('form.company.city'),
                'attr' => [
                    'placeholder' => t('form.company.city'),
                ],
            ])
            ->add(child: 'zipcode', options: [
                'label' => t('form.company.zipcode'),
                'attr' => [
                    'placeholder' => t('form.company.zipcode'),
                ],
            ])
            ->add(child: 'businessId', options: [
                'label' => t('form.company.businessId'),
                'attr' => [
                    'placeholder' => t('form.company.businessId'),
                ],
            ])
            ->add(child: 'vatNumber', options: [
                'label' => t('form.company.vat_number'),
                'attr' => [
                    'placeholder' => t('form.company.vat_number'),
                ],
            ])
            ->add(child: 'info', options: [
                'label' => t('form.company.info'),
                'attr' => [
                    'placeholder' => t('form.company.info'),
                ],
            ])
            ->add(child: 'phone', options: [
                'label' => t('form.company.phone'),
                'attr' => [
                    'placeholder' => t('form.company.phone'),
                ],
            ])
            ->add(child: 'email', options: [
                'label' => t('form.company.email'),
                'attr' => [
                    'placeholder' => t('form.company.email'),
                ],
            ])
            ->add(child: 'website', options: [
                'label' => t('form.company.website'),
                'attr' => [
                    'placeholder' => t('form.company.website'),
                ],
            ])
            ->add(child: 'country', type: EntityType::class, options: [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => t('form.company.country'),
                'attr' => [
                    'placeholder' => t('form.company.country'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
