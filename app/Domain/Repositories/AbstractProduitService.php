<?php
namespace App\Domain\Repositories ;

use App\Domain\Enums\ProduitStatusEnum;
use App\Domain\Interfaces\ProduitsServiceInterface;
use App\Domain\Model\RequestCredit;
use App\Domain\Traits\RequestCreditTrait;
use App\Infrastructure\Persistence\Eloquent\EloquentCreditRepository;
use Illuminate\Support\Str;

abstract class AbstractProduitService implements ProduitsServiceInterface {
    use RequestCreditTrait;

    private RequestCredit $requestCredit;
    protected float $interestRate;
    protected string $produitName;
    protected int $fees;
    protected int $duration;
    protected int $minAmount;
    protected int $maxAmount;


    protected EloquentCreditRepository $creditRepository;

    final public function validateAmount(int $amount): bool
    {

        return $amount >= $this->minAmount && $amount <= $this->maxAmount;
    }

    final public function create(string $phoneNumber, int $amount): RequestCredit
    {
        $valid = $this->validateAmount($amount);

        $requestCredit = new RequestCredit();
        $requestCredit->id = Str::uuid();
        $requestCredit->amount_requested = $amount;
        $requestCredit->product = $this->produitName;
        $requestCredit->status = $valid ? ProduitStatusEnum::OK->value : ProduitStatusEnum::NO->value;
        $requestCredit->dueDate = $valid ? $this->calculateDueDate() : null;
        $requestCredit->phoneNumber = $phoneNumber;
        $requestCredit->amount_to_repay = $valid ? $this->calculateAmountToRepay($amount) : 0;
        $this->creditRepository->save($requestCredit);
        $this->requestCredit = $requestCredit;
        return $requestCredit;
    }

    final public function getMessage(): string
    {

        return $this->createMessage($this->requestCredit);
    }
}
