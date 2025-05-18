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

readonly class DocumentFilterFormService
{
    public function __construct(private FormFactoryInterface $formFactory)
    {
    }

    public function createForm(Company $company): FormInterface
    {
        return
            $this->formFactory->createNamedBuilder('')->setMethod('GET')

            ->add('q', TextType::class, [
                'label' => 'search',
                'required' => false,
            ])
            ->add('state', ChoiceType::class, [
                'choices' => ['NO_PAID' => 'NO_PAID', 'PAID' => 'PAID', 'ALL' => 'ALL', 'OVERDUE' => 'OVERDUE'],
                'data' => 'NO_PAID',
                'label' => 'state',
                'required' => true,
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
                'label' => 'customer',
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('customer')
                        ->andWhere('customer.company = :company')
                        ->setParameter('company', $company)
                        ->orderBy('customer.name', 'ASC');
                },
            ])
            ->add('dateFrom', DateType::class, [
                'label' => 'dateFrom',
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'dateTo',
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