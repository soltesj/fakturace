<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('contact')
            ->add('street')
            ->add('houseNumber')
            ->add('town')
            ->add('zipcode')
            ->add('ic')
            ->add('dic')
            ->add('phone')
            ->add('email')
            ->add('web')
            ->add('bankAccount')

            ->add('country', EntityType::class, [
                'class' => Country::class,
'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
