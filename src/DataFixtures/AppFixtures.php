<?php

namespace App\DataFixtures;

use App\Entity\BankAccount;
use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\DocumentNumbers;
use App\Entity\DocumentPaymentType;
use App\Entity\DocumentType;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\VatLevel;
use App\Entity\DocumentPriceType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const int USER_1 = 1;
    const string USER_1_EMAIL = 'user1@example.com';
    const int USER_2 = 2;
    const string USER_2_EMAIL = 'user2@example.com';
    const int COMPANY_1 = 1;

    const array VAT_LEVELS_DATA = [
        ['amount' => '21.00', 'name' => 'Základní sazba', 'validFrom' => '2013-01-01'],
        ['amount' => '12.00', 'name' => 'Snížená sazba', 'validFrom' => '2024-01-01'],
    ];
    const array PAYMENT_TYPE_DATA = [
        'Příkazem',
        'Hotově',
    ];
    const array PRICE_TYPE_DATA = ['TOTAL_PRICE', 'PARTIAL_PRICE'];

    const array BANK_ACCOUNT_DATA = [
        [
            'sequence' => 1,
            'shortName' => 'ČS',
            'name' => 'Česká Spořitelna',
            'accountNumber' => '012356789',
            'bankCode' => '0800',
            'iban' => 'CZ0123456789012345678901',
            'bic' => 'ABCDFGHI',
        ],
        [
            'sequence' => 1,
            'shortName' => 'KB',
            'name' => 'Komerční Banka',
            'accountNumber' => '10-012356789',
            'bankCode' => '0100',
            'iban' => 'CZ0123456789012345678901',
            'bic' => 'ABCDFGHI',
        ],
    ];

    const array STATUS_DATA = [
        ['reference' => 1, 'name' => 'STATUS_ACTIVE',],
        ['reference' => 2, 'name' => 'STATUS_INACTIVE',],
        ['reference' => 3, 'name' => 'STATUS_ARCHIVED',],
        ['reference' => 4, 'name' => 'STATUS_DELETED',],
    ];

    const array DOCUMENT_TYPES_DATA = [
        ['name' => 'Faktura vydaná', 'defaultFormat' => 'YYYY11000n'],
        ['name' => 'Faktura přijatá', 'defaultFormat' => 'YY210000n'],
        ['name' => 'Faktura zálohová vydaná', 'defaultFormat' => 'YY120000n'],
        ['name' => 'Faktura zálohová přijatá', 'defaultFormat' => 'YY220000n'],
        ['name' => 'Příjem bankovní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Výdej bankovní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Příjem pokladní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Výdej pokladní', 'defaultFormat' => 'YYYY1200n'],
    ];
    const array CUSTOMER_DATA = [
        [
            'name' => 'AGENAS TEAM autoklub v AČR',
            'street' => 'Smetanova',
            'houseNumber' => 300,
            'town' => 'Javorník u Jeseníku',
            'zipcode' => '790 70',
            'companyNumber' => '72060107',
            'vatNumber' => 'CZ00495514',
        ],
        [
            'name' => 'Automoto klub Mohelnice v AČR',
            'street' => 'Líšnice',
            'houseNumber' => 33,
            'town' => 'Mohelnice',
            'zipcode' => '789 85',
            'companyNumber' => '0495514',
            'vatNumber' => 'CZ00495514',
        ],
    ];


    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $country = $this->createCountry();
        $manager->persist($country);
        $currency = $this->createCurrency();
        $manager->persist($currency);
        foreach (self::STATUS_DATA as $status) {
            $status = $this->createStatus($status);
            $manager->persist($status);
        }
        foreach (self::PRICE_TYPE_DATA as $priceTypeName) {
            $priceType = $this->createPriceType($priceTypeName);
            $manager->persist($priceType);
        }
        foreach (self::VAT_LEVELS_DATA as $vatLevelData) {
            $vatLevel = $this->createVatLevel($vatLevelData, $country);
            $manager->persist($vatLevel);
        }
        foreach (self::PAYMENT_TYPE_DATA as $name) {
            $vatLevel = $this->createPaymentType($name);
            $manager->persist($vatLevel);
        }
        $user1 = $this->createUser1();
        $user2 = $this->createUser2();
        $company = $this->createCompany1($country, $currency);
        foreach (self::DOCUMENT_TYPES_DATA as $documentTypeData) {
            $documentType = $this->createDocumentType($documentTypeData);
            $manager->persist($documentType);
            $documentNumber = $this->createDocumentNumber($company, $documentType);
            $manager->persist($documentNumber);
        }
        $company->addUser($user1);
        $company->addUser($user2);
        $manager->persist($company);
        foreach (self::BANK_ACCOUNT_DATA as $bankAccountData) {
            $vatLevel = $this->bankAccount($bankAccountData, $company);
            $manager->persist($vatLevel);
        }
        foreach (self::CUSTOMER_DATA as $customerData) {
            $customer = $this->createCustomer($customerData, $company, $country);
            $manager->persist($customer);
        }
        $manager->flush();
    }

    public function bankAccount(array $data, Company $company): BankAccount
    {
        $status = $this->getReference(Status::class. 1, Status::class);
        assert($status instanceof Status);
        $bankAccount = new BankAccount($company);
        $bankAccount->setSequence($data['sequence']);
        $bankAccount->setName($data['name']);
        $bankAccount->setShortName($data['shortName']);
        $bankAccount->setAccountNumber($data['accountNumber']);
        $bankAccount->setBankCode($data['bankCode']);
        $bankAccount->setIban($data['iban']);
        $bankAccount->setBic($data['bic']);
        $bankAccount->setStatus($status);

        return $bankAccount;
    }

    /**
     * @return Country
     */
    public function createCountry(): Country
    {
        $country = new Country();
        $country->setName('Czech Republic');
        $country->setSname('CZ');
        $country->setIsEU(true);

        return $country;
    }

    public function createCurrency(): Currency
    {
        $currency = new Currency();
        $currency->setCode('CZK');
        $currency->setName('Czech Republic Koruna');
        $currency->setSymbol('Kč');

        return $currency;
    }

    /**
     * @param array{amount: string, name: string, validFrom: string} $data
     */
    public function createVatLevel(array $data, Country $country): VatLevel
    {
        $vatLevel = new VatLevel();
        $vatLevel->setVatAmount($data['amount']);
        $vatLevel->setName($data['name']);
        $vatLevel->setValidFrom(new DateTimeImmutable($data['validFrom']));
        $vatLevel->setCountry($country);

        return $vatLevel;
    }

    public function createPaymentType(string $name): DocumentPaymentType
    {
        $paymentType = new DocumentPaymentType();
        $paymentType->setName($name);

        return $paymentType;
    }

    public function createUser1(): User
    {
        $user = new User();
        $user->setEmail(self::USER_1_EMAIL);
        $user->setRoles(['ROLE_USER', 'ROLE_COMPANY_EDITOR']);
        $user->setName('User_1');
        $user->setSurname('User_1');
        $user->setIsVerified(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $this->setReference(User::class.self::USER_1, $user);

        return $user;
    }

    public function createUser2(): User
    {
        $user = new User();
        $user->setEmail(self::USER_2_EMAIL);
        $user->setRoles(['ROLE_USER']);
        $user->setName('User_2');
        $user->setSurname('User_2');
        $user->setIsVerified(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $this->setReference(User::class.self::USER_2, $user);

        return $user;
    }

    public function createCompany1(Country $country, Currency $currency): Company
    {
        $company = new Company();
        $company->setName('Company_1');
        $company->setCity('Praha');
        $company->setStreet('Ulice 1');
        $company->setBuildingNumber('123');
        $company->setZipcode('12345');
        $company->setVatNumber('CZ1234567890');
        $company->setBusinessId('1234567890');
        $company->setMaturityDays(14);
        $company->setCountry($country);
        $company->addCurrency($currency);
        $company->setIsVatPayer(true);
        $this->setReference(Company::class.self::COMPANY_1, $company);

        return $company;
    }

    /**
     * @param array{name:string, defaultFormat:string} $documentTypeData
     */
    public function createDocumentType(array $documentTypeData): DocumentType
    {
        $documentType = new DocumentType();
        $documentType->setName($documentTypeData['name']);
        $documentType->setDefaultFormat($documentTypeData['defaultFormat']);

        return $documentType;
    }

    public function createDocumentNumber(Company $company, DocumentType $documentType): DocumentNumbers
    {
        $documentNumber = new DocumentNumbers(
            $company,
            $documentType,
            (int)new DateTimeImmutable()->format('Y'),
            $documentType->getDefaultFormat()
        );
        $documentNumber->setNextNumber(1);

        return $documentNumber;
    }

    private function createStatus(array $statusData): Status
    {
        $staus = new Status();
        $staus->setName($statusData['name']);
        $this->setReference(Status::class.$statusData['reference'], $staus);

        return $staus;
    }

    private function createCustomer($customerData, Company $company, Country $country): Customer
    {
        $status = $this->getReference(Status::class. 1, Status::class);
        assert($status instanceof Status);
        $customer = new Customer($company);
        $customer->setName($customerData['name']);
        $customer->setStreet($customerData['street']);
        $customer->setHouseNumber($customerData['houseNumber']);
        $customer->setZipcode($customerData['zipcode']);
        $customer->setTown($customerData['town']);
        $customer->setCountry($country);
        $customer->setCompanyNumber($customerData['companyNumber']);
        $customer->setVatNumber($customerData['vatNumber']);
        $customer->setStatus($status);

        return $customer;
    }

    private function createPriceType(mixed $priceTypeName): DocumentPriceType
    {
        $priceType = new DocumentPriceType();
        $priceType->setName($priceTypeName);

        return $priceType;
    }

}
