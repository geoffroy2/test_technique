<?php
namespace App\Domain\Services\Products;

use App\Domain\Services\AbstractProduitService;
use App\Infrastructure\Persistence\Eloquent\EloquentCreditRepository;

final class NanoCreditService extends AbstractProduitService {
    protected float $interestRate = 6.2;
    protected string $produitName = 'nanoCredit';
    protected int $fees = 1000;
    protected int $duration = 90;
    protected int $minAmount = 100001;
    protected int $maxAmount = 300000;

    protected EloquentCreditRepository $creditRepository ;
    public function __construct(EloquentCreditRepository $eloquentCreditRepository)
    {
        $this->creditRepository  = $eloquentCreditRepository ;
    }
}
