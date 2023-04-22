<?php

namespace App\Domain\Interfaces;

use App\Domain\Model\RequestCredit;
use App\Http\Requests\CreateRequestCredit;

interface CreditRepositoryInterface
{
    public function createRequestCredit(array $data);
}
