<?php

namespace App\Domain\Dto;
class CreateRequestCreditDto
{
    public $amount_requested;
    public $phoneNumber;
    public $code;

    public function __construct($phoneNumber, $amount_requested, $code)
    {
        $this->code = $code;
        $this->amount_requested = $amount_requested;
        $this->phoneNumber = $phoneNumber;
    }
}
