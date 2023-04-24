<?php

namespace App\Domain\Services ;
use App\Application\Requests\CreateRequestCredits;
use App\Domain\Dto\CreateRequestCreditDto;
use App\Domain\Enums\ProduitNameEnum;
use App\Domain\Exceptions\CustomException;
use App\Domain\Interfaces\ProduitsServiceInterface;
use App\Domain\Services\Products\MicroCreditService;
use App\Domain\Services\Products\NanoCreditService;
use App\Domain\Services\Products\PicoCreditService;
use App\Infrastructure\Persistence\Eloquent\EloquentCreditRepository;

class  RequestCreditService {
    private $creditRepository;

    public function __construct(EloquentCreditRepository $eloquentCreditRepository) {
        $this->creditRepository = $eloquentCreditRepository ;
    }

    private function getProduit($code,$creditRepository): ProduitsServiceInterface
    {

        switch ($code) {
            case ProduitNameEnum::P->value:
                return new PicoCreditService($creditRepository);
            case ProduitNameEnum::M->value:
                return new MicroCreditService($creditRepository);
            case ProduitNameEnum::N->value:
                return new NanoCreditService($creditRepository);
            default:
                throw new CustomException('Unexpected error occurred', 500);
                break;
        }
    }

    public function create(CreateRequestCreditDto $createRequestCreditDTO) {
        if (in_array($createRequestCreditDTO->code, ['picoCredit','nanoCredit','microCredit']))
        {
            $produit = $this->getProduit($createRequestCreditDTO->code,$this->creditRepository);
            $produit->create($createRequestCreditDTO->phoneNumber, $createRequestCreditDTO->amount_requested);
            return $produit->getMessage();
        }else{
            throw new CustomException('Code produit non valide', 400);
        }
    }

    public function getAllCreditsRequest() {
        try {
            $credits = $this->creditRepository->findAll();
            return $credits;
        } catch (CustomException $e) {
            throw new CustomException($e->getMessage(),500);
        }
    }
}
