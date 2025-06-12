<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'name', options: [
                'label' => t('form.user.name'),
                'attr' => [
                    'placeholder' => t('form.user.name.placeholder'),
                ],
            ])
            ->add(child: 'surname', options: [
                'label' => t('form.user.surname'),
                'attr' => [
                    'placeholder' => t('form.user.suname.placeholder'),
                ],
            ])
            ->add(child: 'email', options: [
                'label' => t('form.user.email'),
                'attr' => [
                    'placeholder' => t('form.user.email.placeholder'),
                ],
            ])
            ->add(child: 'phone', options: [
                'label' => t('form.user.phone'),
                'attr' => [
                    'placeholder' => t('form.user.phone.placeholder'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
