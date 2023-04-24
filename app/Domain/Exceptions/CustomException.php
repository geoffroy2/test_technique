<?php

namespace App\Domain\Exceptions;

use Throwable;

class CustomException extends \Exception implements Throwable
{
    protected $message = 'Code produit non valide.';
    protected $code = 404;

    public function __construct(string $message,$code,Throwable $previous = null)
    {
        $this->message = $message ;
        $this->code = $code ;
        parent::__construct($this->message, $this->code, $previous);
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'status_code' => $this->code,
            'error' => true,
            'message' => $this->message,
        ],$this->code);
    }
}
