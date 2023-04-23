<?php

namespace App\Domain\Interfaces;

use App\Application\Requests\CreateRequestCredits;
use App\Domain\Model\RequestCredit;
use App\Http\Requests\CreateRequestCredit;

interface CreditRepositoryInterface
{
    public function save(RequestCredit $createRequestCredits);
    public function findAll();
}
