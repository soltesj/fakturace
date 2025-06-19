<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\Document;
use App\Entity\DocumentPaymentType;
use App\Enum\VatMode;
use App\Status\StatusValues;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class DocumentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Document $document */
        $document = $options['data'];
        $company = $document->getCompany();
        $customer = $document->getCustomer();
        $builder
            ->add('documentNumber', options: [
                'label' => t('form.invoice.document_number'),
                'attr' => [
                    'placeholder' => t('form.invoice.document_number.placeholder'),
                    'readonly' => true,
                ],
            ])
            ->add('variableSymbol', options: [
                'label' => t('form.invoice.variable_symbol'),
                'attr' => [
                    'placeholder' => t('form.invoice.variable_symbol.placeholder'),
                ],
            ])
            ->add('paymentType', EntityType::class, [
                'class' => DocumentPaymentType::class,
                'choice_label' => 'name',
                'label' => t('form.invoice.payment_type'),
            ])
            ->add('dateIssue', options: [
                'label' => t('form.invoice.date_issue'),
                'attr' => [
                    'placeholder' => t('form.invoice.date_issue.placeholder'),
                ],
            ])
            ->add('dateTaxable', options: [
                'label' => t('form.invoice.date_taxable'),
                'attr' => [
                    'placeholder' => t('form.invoice.date_taxable.placeholder'),
                ],
            ])
            ->add('dateDue', options: [
                'label' => t('form.invoice.date_due'),
                'attr' => [
                    'placeholder' => t('form.invoice.date_due.placeholder'),
                ],
            ])
            ->add('bankAccount', EntityType::class, [
                'class' => BankAccount::class,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('bank_account')
                        ->andWhere('bank_account.company = :company')
                        ->setParameter('company', $company)
                        ->andWhere('bank_account.status = :status')
                        ->setParameter('status', StatusValues::STATUS_ACTIVE)
                        ->orderBy('bank_account.sequence', 'ASC');
                },
                'choice_label' => 'name',
                'label' => t('form.invoice.bank_account'),
                'attr' => [
                    'placeholder' => t('form.invoice.bankAccount.placeholder'),
                ],
            ])
            ->add('customerId', HiddenType::class, [
                'mapped' => false,
                'required' => true,
                'data' => $customer?->getId(),
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'query_builder' => function (EntityRepository $er) use ($company): QueryBuilder {
                    return $er->createQueryBuilder('currency')
                        ->andWhere('currency.id in( :companyCurrencies)')
                        ->setParameter('companyCurrencies',
                            $company->getCurrency())
                        ->orderBy('currency.code', 'ASC');
                },
                'choice_label' => 'code',
                'label' => t('form.invoice.currency'),
                'attr' => [
                    'placeholder' => t('form.invoice.currency.placeholder'),
                ],
            ])
            ->add('rate', options: [
                'label' => t('form.invoice.currency_rate'),
                'attr' => [
                    'placeholder' => t('form.invoice.currency_rate.placeholder'),
                ],
            ])
            ->add('note', options: [
                'label' => t('form.invoice.note'),
                'attr' => [
                    'placeholder' => t('form.invoice.note.placeholder'),
                ],
            ])
            ->add('description', options: [
                'label' => t('form.invoice.description'),
                'attr' => [
                    'placeholder' => t('form.invoice.description.placeholder'),
                ],
            ])
            ->add('priceWithoutHighVat', HiddenType::class)
            ->add('priceWithoutLowVat', HiddenType::class)
            ->add('priceNoVat', HiddenType::class)
            ->add('priceTotal', HiddenType::class)
            ->add('documentItems', CollectionType::class, [
                'entry_type' => DocumentItemFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('vatMode', ChoiceType::class, [
                'choices' => VatMode::cases(),
                //$this->vatModeService->getAvailableVatModes($company,$document->getCustomer()),
                'choice_label' => fn(VatMode $mode) => $mode->label(),
                'choice_value' => fn(?VatMode $mode) => $mode?->value,
//                'data' => $this->vatModeService->getDefaultVatMode($company)->value,
                'label' => t('form.invoice.vat_mode'),
                'required' => true,
                'attr' => [
                    'placeholder' => t('form.invoice.vat_mode'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'document_types',
        ]);
    }
}
