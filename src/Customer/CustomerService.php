<?php

namespace App\Customer;

use App\Entity\Customer;
use App\Repository\StatusRepository;
use App\Status\StatusValues;

readonly class CustomerService
{
    public function __construct(
        private StatusRepository $statusRepository,
    ) {
    }

    public function assignCustomerStatus(Customer $customer): Customer
    {
        $activeStatus = $this->statusRepository->find(StatusValues::STATUS_ACTIVE);
        $archivedStatus = $this->statusRepository->find(StatusValues::STATUS_ARCHIVED);
        if (count($customer->getDocuments())) {
            $customerNew = clone $customer;
            $customerNew->setStatus($activeStatus);
            $customer->setStatus($archivedStatus);

            return $customerNew;
        }

        return $customer;
    }
}