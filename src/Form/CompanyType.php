<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isVatPayer')
            ->add('name')
//            ->add('alias')
            ->add('contact')
            ->add('street')
            ->add('buildingNumber')
            ->add('city')
            ->add('zipcode')
            ->add('ic')
            ->add('dic')
            ->add('info')
            ->add('phone')
            ->add('email')
            ->add('website')
            ->add('emailInvoiceMessage')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
