<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\DocumentPrice;
use App\Entity\VatLevel;
use App\Entity\DocumentPriceType as PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentPriceTotalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('amount');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentPrice::class,
        ]);
    }
}
