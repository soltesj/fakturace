<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\DocumentNumbers;
use App\Entity\DocumentType;
use App\Entity\User;
use App\Entity\VatLevel;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const USER_1 = 1;
    const USER_1_EMAIL = 'user1@example.com';
    const USER_2 = 2;
    const COMPANY_1 = 1;

    const VAT_LEVELS_DATA = [
        ['amount' => 21.00, 'name' => 'Základní sazba', 'validFrom' => '2013-01-01'],
        ['amount' => 12.00, 'name' => 'Snížená sazba', 'validFrom' => '2024-01-01'],
    ];

    const DOCUMENT_TYPES_DATA = [
        ['name' => 'Faktura vydaná', 'defaultFormat' => 'YYYY11000n'],
        ['name' => 'Faktura přijatá', 'defaultFormat' => 'YY210000n'],
        ['name' => 'Faktura zálohová vydaná', 'defaultFormat' => 'YY120000n'],
        ['name' => 'Faktura zálohová přijatá', 'defaultFormat' => 'YY220000n'],
        ['name' => 'Příjem bankovní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Výdej bankovní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Příjem pokladní', 'defaultFormat' => 'YYYY1200n'],
        ['name' => 'Výdej pokladní', 'defaultFormat' => 'YYYY1200n'],
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
        foreach (self::VAT_LEVELS_DATA as $vatLevelData) {
            $vatLevel = $this->createVatLevel($vatLevelData, $country);
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
        $manager->flush();
    }

    /**
     * @return Country
     */
    public function createCountry(): Country
    {
        $country = new Country();
        $country->setName('Czech Republic');
        $country->setSname('CZ');

        return $country;
    }

    public function createCurrency(): Currency
    {
        $currency = new Currency();
        $currency->setCurrencyCode('CZK');
        $currency->setCurrencyName('Czech Republic Koruna');
        $currency->setCurrencySymbol('Kč');

        return $currency;
    }

    public function createVatLevel(array $data, Country $country): VatLevel
    {
        $vatLevel = new VatLevel();
        $vatLevel->setVatAmount($data['amount']);
        $vatLevel->setName($data['name']);
        $vatLevel->setValidFrom(new DateTimeImmutable($data['validFrom']));
        $vatLevel->setCountry($country);

        return $vatLevel;
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
        $user->setEmail('user2@example.com');
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
        $company->setDic('CZ1234567890');
        $company->setIc('1234567890');
        $company->setMaturityDays(14);
        $company->setCountry($country);
        $company->addCurrency($currency);
        $this->setReference(Company::class.self::COMPANY_1, $company);

        return $company;
    }

    public function createDocumentType(array $documentTypeData): DocumentType
    {
        $documentType = new DocumentType();
        $documentType->setName($documentTypeData['name']);
        $documentType->setDefaultFormat($documentTypeData['defaultFormat']);

        return $documentType;
    }

    public function createDocumentNumber(Company $company, DocumentType $documentType): DocumentNumbers
    {
        $documentNumber = new DocumentNumbers($company, $documentType, new DateTimeImmutable()->format('Y'),
            $documentType->getDefaultFormat());
        $documentNumber->setNextNumber(1);

        return $documentNumber;
    }
}
