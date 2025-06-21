<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Currency;
use App\Enum\VatPaymentMode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class CompanyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'name', options: [
                'label' => t('form.company.name'),
                'attr' => [
                    'placeholder' => t('form.company.name'),
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
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('currency')
                        ->orderBy('currency.name', 'ASC');

                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
