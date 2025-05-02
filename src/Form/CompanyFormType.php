<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Currency;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name',TextType::class)
            ->add('street')
            ->add('buildingNumber')
            ->add('city')
            ->add('zipcode')
            ->add('businessId')
            ->add('vatNumber')
            ->add('isVatPayer')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('currency')
                        ->orderBy('currency.currencyName', 'ASC');

                },
                'choice_label' => 'currencyName',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('vatPayer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
