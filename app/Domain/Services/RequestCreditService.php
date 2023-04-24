<?php

namespace App\Domain\Services ;
use App\Application\Requests\CreateRequestCredits;
use App\Domain\Exceptions\CustomException;
use App\Domain\Exceptions\InvalidProductCodeException;
use App\Domain\Model\RequestCredit;
use App\Infrastructure\Persistence\Eloquent\EloquentCreditRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;
class  RequestCreditService {
    private $creditRepository;

    public function __construct(EloquentCreditRepository $eloquentCreditRepository) {
            $this->creditRepository = $eloquentCreditRepository ;
    }

    public function create(CreateRequestCredits $data) {
        try {
            $product = $this->getProductByCode($data['code']);

            $amountRequested = $data['amount_requested'];

            if ((($amountRequested < 5000 || $amountRequested > 100000) && $data['code'] == 'picoCredit') ||
                (($amountRequested < 100001 || $amountRequested > 300000) && $data['code'] == 'nanoCredit') ||
                (($amountRequested < 300001 || $amountRequested > 500000) && $data['code'] == 'microCredit')
            ) {
                return $this->createRejectedRequestCredit($data, $product);
            }

            $interestRate = $product['interest_rate'];
            $creditFeesAmount = $product['credit_fees_amount'];
            $durationInDays = $product['duration_in_days'];

            $amountToRepay = $this->calculateAmountToRepay($amountRequested, $interestRate, $creditFeesAmount);

            $dueDate = $this->calculateDueDate($durationInDays);

            $requestCredit = new RequestCredit();

            $requestCredit->id = Str::uuid();
            $requestCredit->amount_requested = $amountRequested;
            $requestCredit->product = $data['code'];
            $requestCredit->status = $product['status'];
            $requestCredit->dueDate = $dueDate;
            $requestCredit->phoneNumber = $data['phoneNumber'];
            $requestCredit->amount_to_repay = $amountToRepay;

            $this->creditRepository->save($requestCredit);

            return $requestCredit;
        } catch (InvalidProductCodeException $e) {
            throw new CustomException('Code produit non valide', 400);
        } catch (\Throwable $e) {
            throw new CustomException('Unexpected error occurred', 500);
        }
    }

    public function getProductByCode(string $code) {
        $products = [
            "picoCredit" => [
                'credit_fees_amount' => 500,
                'interest_rate' => 1.8,
                'duration_in_days' => 30,
            ],
            "nanoCredit" => [
                'credit_fees_amount' => 1000,
                'interest_rate' => 6.2,
                'duration_in_days' => 90,
            ],
            "microCredit" => [
                'credit_fees_amount' => 1000,
                'interest_rate' => 6.2,
                'duration_in_days' => 90,
            ]
        ];

        if (array_key_exists($code, $products)) {
            $product = $products[$code];
            $product['status'] = 'accordé';
            return $product;
        }
        throw new InvalidProductCodeException();
    }

    private function createRejectedRequestCredit(CreateRequestCredits $data, array $product)
    {
        $requestCredit = new RequestCredit();
        $requestCredit->id = Str::uuid();
        $requestCredit->amount_requested = $data['amount_requested'];
        $requestCredit->product = $data['code'];
        $requestCredit->status = 'rejeté';
        $requestCredit->phoneNumber = $data['phoneNumber'];
        $requestCredit->amount_to_repay = 0;

        $this->creditRepository->save($requestCredit);

        return $requestCredit;
    }

    private function calculateAmountToRepay(float $amountRequested, float $interestRate, float $creditFeesAmount)
    {
        return $amountRequested + ($interestRate * $amountRequested) / 100 + $creditFeesAmount;
    }

    private function calculateDueDate(int $durationInDays)
    {
        $dateNow = Carbon::now();
        return $dateNow->addDays($durationInDays);
    }

    public function createMessage($requestCredit){
        if($requestCredit->status == 'accordé') {
            $formatDate  = $requestCredit->dueDate->format('d-m-Y') ;
            $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $formatDate. Le montant à rembourser est de $requestCredit->amount_to_repay CFA " ;
        } else {
            $message = "Cher client vous ne pouvez pas prendre de crédit parce que le montant $requestCredit->amount_requested CFA ne fait pas partir de la grille de ce produit " ;
        }
        return $message ;
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
