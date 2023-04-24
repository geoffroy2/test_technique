<?php

namespace App\Domain\Interfaces;

use App\Domain\Model\RequestCredit;

interface ProduitsServiceInterface
{
    public function validateAmount(int $amount): bool;
    public function create(string $phoneNumber, int $amount): RequestCredit;
    public function getMessage(): string;
}
