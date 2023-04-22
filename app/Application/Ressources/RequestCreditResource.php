<?php

namespace App\Application\Ressources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestCreditResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount_requested' => $this->amount_requested,
            'product' => $this->product,
            'status' => $this->status,
            'dueDate' => $this->dueDate,
            'phoneNumber' => $this->phoneNumber,
            'amount_to_repay' => $this->amount_to_repay,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
