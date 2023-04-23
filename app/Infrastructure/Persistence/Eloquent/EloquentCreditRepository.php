<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Requests\CreateRequestCredits;
use App\Domain\Exceptions\CustomException;
use App\Domain\Exceptions\InvalidProductCodeException;
use App\Domain\Interfaces\CreditRepositoryInterface;
use App\Domain\Model\RequestCredit;
use App\Http\Requests\CreateRequestCredit;
//use App\Models\RequestCredits;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;


class EloquentCreditRepository implements CreditRepositoryInterface
{

    private $model ;

    public function __construct() {
        $this->model = new RequestCredit;
    }
    public function  save(RequestCredit $data) {
        $this->model = $data ;
        return $this->model->save() ;
    }

    public function findAll()
    {
        return $this->model->all()->toArray() ;
    }

//    public function createRequestCredit(CreateRequestCredits $data)
//    {
//
//        $product = $this->createProduct($data['code']);
//
//        $amountRequested = $data['amount_requested'];
//
//        if ((($amountRequested < 5000 || $amountRequested > 100000) && $data['code'] == 'picoCredit') ||
//            (($amountRequested < 100001 || $amountRequested > 300000) && $data['code'] == 'nanoCredit') ||
//            (($amountRequested < 300001 || $amountRequested > 500000) && $data['code'] == 'microCredit')
//        ) {
//            return $this->createRejectedRequestCredit($data);
//        }
//
//        try {
//            $interestRate = $product['interest_rate'];
//            $creditFeesAmount = $product['credit_fees_amount'];
//            $durationInDays = $product['duration_in_days'];
//
//            $amountToRepay = $this->calculateAmountToRepay($amountRequested, $interestRate, $creditFeesAmount);
//
//            $dueDate = $this->calculateDueDate($durationInDays);
//
//            $requestCredit = new RequestCredit();
//
//            $requestCredit->id = Str::uuid();
//            $requestCredit->amount_requested = $amountRequested;
//            $requestCredit->product = $data['code'];
//            $requestCredit->status = $product['status'];
//            $requestCredit->dueDate = $dueDate;
//            $requestCredit->phoneNumber = $data['phoneNumber'];
//            $requestCredit->amount_to_repay = $amountToRepay;
//
//           $requestCredit->save();
//
//            return $requestCredit;
//        } catch (Exception $e) {
//            throw new CustomException($e->getMessage(),500) ;
//
//        }
//    }


//    private function createProduct(string $code): array
//    {
//        $products = [
//            "picoCredit" => [
//                'credit_fees_amount' => 500,
//                'interest_rate' => 1.8,
//                'duration_in_days' => 30,
//            ],
//            "nanoCredit" => [
//                'credit_fees_amount' => 1000,
//                'interest_rate' => 6.2,
//                'duration_in_days' => 90,
//            ],
//            "microCredit" => [
//                'credit_fees_amount' => 1000,
//                'interest_rate' => 6.2,
//                'duration_in_days' => 90,
//            ]
//        ];
//
//        if (array_key_exists($code, $products)) {
//            $product = $products[$code];
//            $product['status'] = 'accordé';
//            return $product;
//        }
//        throw new InvalidProductCodeException();
//    }
//
//
//    private function calculateAmountToRepay(float $amountRequested, float $interestRate, float $creditFeesAmount): float
//    {
//        return $amountRequested + ($interestRate * $amountRequested) / 100 + $creditFeesAmount;
//    }
//
//    private function calculateDueDate(int $durationInDays): Carbon
//    {
//        $dateNow = Carbon::now();
//        return $dateNow->addDays($durationInDays);
//    }

//    private function createRejectedRequestCredit(CreateRequestCredits $data)
//    {
//
//        try {
//            $requestCredit = new RequestCredit();
//            $requestCredit->id = Str::uuid();
//            $requestCredit->amount_requested = $data['amount_requested'];
//            $requestCredit->product = $data['code'] ;
//            $requestCredit->status = 'rejeté' ;
//            $requestCredit->phoneNumber = $data['phoneNumber'] ;
//            $requestCredit->save() ;
//            return $requestCredit ;
//        } catch (Exception $e) {
//            throw new CustomException($e->getMessage(),500) ;
//        }
//
//    }
//
//    public function createMessage($requestCredit){
//        if($requestCredit->status == 'accordé') {
//            $formatDate  = $requestCredit->dueDate->format('d-m-Y') ;
//            $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $formatDate. Le montant à rembourser est de $requestCredit->amount_to_repay CFA " ;
//        } else {
//            $message = "Cher client vous ne pouvez pas prendre de crédit parce que le montant $requestCredit->amount_requested CFA ne fait pas partir de la grille de ce produit " ;
//        }
//        return $message ;
//    }





}
