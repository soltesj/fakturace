<?php

namespace App\Form;

use App\Entity\DocumentItem;
use App\Entity\VatLevel;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentItemFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'name', options: ['attr' => ['placeholder' => 'invoice.item_name']])
            ->add(child: 'quantity', options: [
                'data' => '1',
                'attr' => [
                    'placeholder' => 'invoice.item_quantity',
                ],
            ])
            ->add(child: 'unit', options: [
                'data' => 'ks',
                'attr' => ['placeholder' => 'invoice.item_price_of_uniy_of_meaure'],
            ])
            ->add(child: 'price', options: ['attr' => ['placeholder' => 'invoice.item_price_of_uniy_of_meaure']])
            ->add(child: 'vat', type: EntityType::class, options: [
                'class' => VatLevel::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('vat_level')
                        ->andWhere('vat_level.validTo is null or vat_level.validTo >= :now')
                        ->setParameter('now', new DateTime())
                        ->orderBy('vat_level.vatAmount', 'DESC');
                },
                'choice_label' => 'vatAmount',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentItem::class,
        ]);
    }
}
