<?php

namespace App\Form;

use App\Message\InvoiceEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

class SendEmailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('documentId', HiddenType::class)
            ->add('username', HiddenType::class)
            ->add('to', EmailType::class, [
                'label' => t('document.send_by_email.email_to'),
                'attr' => ['autocomplete' => 'email',],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'document.send_by_email.email_to.not_blank',
                    ]),
                ],
            ])
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
                'attr' => ['rows' => 7],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'document.send_by_email.content.not_blank',
                    ]),
                    new  Length(min: 4, max: 1000),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceEmail::class,
        ]);
    }
}
