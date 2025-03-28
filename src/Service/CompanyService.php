<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\User;
use App\Service\BaseService;

class CompanyService extends BaseService
{
    public function createCompany(Company $company, User $user): Company 
    {
        $this->entityManager->persist($company);
        $user->setCompany($company);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $company;
    }
}