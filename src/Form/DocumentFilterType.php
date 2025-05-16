<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DocumentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['data']);
        $builder->setMethod('GET')
            ->add('q', TextType::class, [
                'label' => 'search',
                'required' => false,
                'attr' => [
                    'placeholder' => 'search',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('state', ChoiceType::class, [
                'choices' => ['NO_PAID' => 'NO_PAID', 'PAID' => 'PAID', 'ALL' => 'ALL', 'OVERDUE' => 'OVERDUE'],
                'label' => 'state',
                'required' => false,
                'attr' => [
                    'placeholder' => 'state',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('customer', TextType::class, [
                'label' => 'customer',
                'required' => false,
                'attr' => [
                    'placeholder' => 'customer',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('dateFrom', DateType::class, [
                'label' => 'dateFrom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateFrom',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'dateTo',
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateTo',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ]);
    }
}