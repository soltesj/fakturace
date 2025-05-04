<?php

namespace App\CompanyRegistry;

readonly class CompanyDTO
{
    public function __construct(
        public string $name,
        public string $vatNumber,
        public string $street,
        public string $houseNumber,
        public string $town,
        public string $zipcode,
        public bool $isVatPayer,
    ) {
    }

}