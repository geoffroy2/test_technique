<?php
namespace App\Domain\Services\Products;

use App\Domain\Services\AbstractProduitService;
use App\Infrastructure\Persistence\Eloquent\EloquentCreditRepository;

final class PicoCreditService extends AbstractProduitService {
    protected float $interestRate = 1.8;

    protected string $produitName = 'picoCredit';

    protected int $fees = 500;
    protected int $duration = 30;
    protected int $minAmount = 5000;
    protected int $maxAmount = 100000;


    protected EloquentCreditRepository $creditRepository ;
    public function __construct(EloquentCreditRepository $eloquentCreditRepository)
    {
            $this->creditRepository  = $eloquentCreditRepository ;
    }
}
