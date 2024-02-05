<?php

namespace App\Form;

use App\Entity\DocumentItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', options: [

            'label' => 'document Type',
            'attr' => [
                'placeholder' => 'name',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add('quantity', options: [
                'label' => 'quantity',
                'attr' => [
                    'placeholder' => 'quantity',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('unit', options: [
                'label' => 'unit',
                'attr' => [
                    'placeholder' => 'unit',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('price', options: [
                'label' => 'price',
                'attr' => [
                    'placeholder' => 'price',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('vat', options: [
                'label' => 'vat',
                'attr' => [
                    'placeholder' => 'vat',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('priceWithVat', options: [
                'label' => 'priceWithVat',
                'attr' => [
                    'placeholder' => 'priceWithVat',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentItem::class,
        ]);
    }
}
