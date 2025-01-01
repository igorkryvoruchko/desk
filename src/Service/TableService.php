<?php

namespace App\Service;

use App\Service\BaseService;

class TableService extends BaseService
{    
    public function create($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}