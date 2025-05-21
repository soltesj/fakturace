<?php

namespace App\Form;

use App\Entity\DocumentNumbers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class DocumentNumbersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'numberFormat', options: [
                'label' => t('document_number.format'),
                'attr' => [
                    'placeholder' => t('document_number.format'),
                ],
            ])
            ->add(child: 'nextNumber', options: [
                'label' => t('document_number.next_number'),
                'attr' => [
                    'placeholder' => t('document_number.next_number'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentNumbers::class,
        ]);
    }
}
