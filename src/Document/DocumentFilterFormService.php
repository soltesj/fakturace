<?php

namespace App\Document;

use App\Entity\Company;
use App\Entity\Customer;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

readonly class DocumentFilterFormService
{
    public function __construct(private FormFactoryInterface $formFactory, private TranslatorInterface $t)
    {
    }

    public function createForm(Company $company): FormInterface
    {
        $a = [
            'NO_PAID' => $this->t->trans('state.no_paid'),
            'PAID' => $this->t->trans('state.paid'),
            'ALL' => $this->t->trans('state.all'),
            'OVERDUE' => $this->t->trans('state.overdue'),
        ];

        return
            $this->formFactory->createNamedBuilder('')->setMethod('GET')

            ->add('q', TextType::class, [
                'label' => t('form.filter.query'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('form.filter.query_placeholder'),
                ],
            ])
            ->add('state', ChoiceType::class, [
                'choices' => array_flip($a),
                'data' => 'NO_PAID',
                'label' => t('form.filter.state'),
                'required' => true,
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'label' => t('form.filter.customer'),
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('customer')
                        ->andWhere('customer.company = :company')
                        ->setParameter('company', $company)
                        ->orderBy('customer.name', 'ASC');
                },
            ])
            ->add('dateFrom', DateType::class, [
                'label' => t('form.filter.date_from'),
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'label' => t('form.filter.date_to'),
                'required' => false,
            ])
            ->getForm();
    }

    /**
     * @param array<string,string|int|null> $data
     * @param DateTimeInterface $dateFrom
     * @return array<string|int|DateTime|Customer|null>
     */
    public function handleFrom(array $data, DateTimeInterface $dateFrom): array
    {
        if ($data['dateFrom'] !== null) {
            /** @var DateTimeInterface $dateFrom */
            $dateFrom = $data['dateFrom'];
        }
        /** @var DateTimeInterface $dateTo */
        $dateTo = $data['dateTo']??null;
        /** @var string|null $state */
        $state = $data['state'] ?? null;
        /** @var Customer|null $customer */
        $customer = $data['customer'] ?? null;
        /** @var string|null $q */
        $q = $data['q'] ?? null;

        return array($q, $dateFrom, $dateTo, $customer, $state);
    }
}