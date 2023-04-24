<?php

namespace App\Domain\Dto;

use App\Domain\Model\RequestCredit;

class RequestCreditDTO
{
    public string $creationDate;
    public float $amount_requested;
    public string $product;
    public string $status;
    public string $dueDate;
    public string $phoneNumber;
    public float $amount_to_repay;

    public static function fromModel($requestCredit): self
    {
        $dto = new self();
        $dto->creationDate = $requestCredit->creationDate;
        $dto->amount_requested = $requestCredit->amount_requested;
        $dto->product = $requestCredit->product;
        $dto->status = $requestCredit->status;
        $dto->dueDate = $requestCredit->dueDate != null ? $requestCredit->dueDate : '';
        $dto->phoneNumber = $requestCredit->phoneNumber;
        $dto->amount_to_repay = $requestCredit->amount_to_repay != null ? $requestCredit->amount_to_repay : 0.0;

        return $dto;
    }
}
