<?php

namespace App\Domain\Exceptions;

use Throwable;

class InvalidProductCodeException extends CustomException
{
    protected $message = 'Le code produit n\'est pas valide.';
    protected $code = 404;

    public function __construct(Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }
}
