<?php

namespace App\Domain\Traits;

use App\Domain\Enums\ProduitStatusEnum;
use App\Domain\Model\RequestCredit;
use Carbon\Carbon;

trait RequestCreditTrait {
    private function calculateAmountToRepay(float $amount)
    {
        return $amount + ($this->interestRate * $amount) / 100 + $this->fees;
    }

    private function calculateDueDate()
    {
        $dateNow = Carbon::now();
        return $dateNow->addDays($this->duration);
    }

    public function createMessage(RequestCredit $requestCredit){

        if($requestCredit->status == ProduitStatusEnum::OK->value) {
            $formatDate  = $requestCredit->dueDate->format('d-m-Y') ;
            $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $formatDate. Le montant à rembourser est de $requestCredit->amount_to_repay CFA " ;
        } else {
            $message = "Cher client vous ne pouvez pas prendre de crédit parce que le montant $requestCredit->amount_requested CFA ne fait pas partir de la grille de ce produit " ;
        }

        return $message ;
    }
}
