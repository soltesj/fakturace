<?php

namespace App\Form;

use App\Entity\EmailTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

class EmailTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'subject', options: [
                'label' => t('document.send_by_email.subject'),
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'document.send_by_email.subject.not_blank',
                    ]),
                    new  Length(min: 4, max: 255),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => t('document.send_by_email.content'),
                'attr' => [
                    'rows' => 7,
//                    'maxlength' => 2000,
//                    'minlength' => 4,
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => t('document.send_by_email.content.not_blank'),
                    ]),
                    new  Length(
                        min: 10,
                        max: 2000,
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmailTemplate::class,
        ]);
    }
}
