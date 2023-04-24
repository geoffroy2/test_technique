<?php

namespace App\Domain\Enums;

enum ProduitStatusEnum : string
{
    case OK = 'accordé';
    case NO = 'rejeté';
}
