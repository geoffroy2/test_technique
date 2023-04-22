<?php

namespace App\Infrastructure\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRequestCredit;

use App\Models\Post;
use App\Models\RequestCredits;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception ;
class RequestCreditController extends Controller
{
    private $creditFeesAmount ;
    private $interestRate ;
    private $durationInDays ;
    private $amount_to_repay;
    private $dateNow;
    private $dueDate ;
    private $dateFormat;
    public function store(CreateRequestCredit $createRequestCredit) {
        $product = $this->createProduct($createRequestCredit);

        $requestCredit = $this->createRequestCredit($createRequestCredit, $product);

        $message = $this->createMessage($requestCredit);

        return response()->json([
            'status_code' => 201,
            'message' => $message,
        ]);
    }

    private function createProduct(CreateRequestCredit $createRequestCredit)
    {
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

        if (array_key_exists($createRequestCredit->code, $products)) {
            $product = $products[$createRequestCredit->code];
            $product['status'] = 'accordé';
            return $product;
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 500,
            'error' => true,
            'message' => 'Code produit non valide.',
        ]));
    }

    private function createRequestCredit(CreateRequestCredit $createRequestCredit, array $product)
    {
        $amountRequested = $createRequestCredit->amount_requested;

        if ((($amountRequested < 5000 || $amountRequested > 100000) && $createRequestCredit->code == 'picoCredit' ) ||
            (($amountRequested < 100001 || $amountRequested > 300000) && $createRequestCredit->code == 'nanoCredit' ) ||
            (($amountRequested < 300001 || $amountRequested > 500000) && $createRequestCredit->code == 'microCredit' )
        ) {

            return $this->createRejectedRequestCredit($createRequestCredit);
        }

        try {
            $interestRate = $product['interest_rate'];
            $creditFeesAmount = $product['credit_fees_amount'];
            $durationInDays = $product['duration_in_days'];

            $amountToRepay = $this->calculateAmountToRepay($amountRequested, $interestRate, $creditFeesAmount);

            $dueDate = $this->calculateDueDate($durationInDays);

            $requestCredit = new RequestCredits();
            $requestCredit->id = Str::uuid();
            $requestCredit->amount_requested = $amountRequested;
            $requestCredit->product = $createRequestCredit->code;
            $requestCredit->status = $product['status'];
            $requestCredit->dueDate = $dueDate;
            $requestCredit->phoneNumber = $createRequestCredit->phoneNumber;
            $requestCredit->amount_to_repay = $amountToRepay;
            $requestCredit->save();

            return $requestCredit;
        } catch (Exception $e) {
            throw new HttpResponseException(response()->json([
                'success'=> false,
                'status_code'=> 500,
                'error'=> true,
                'message'=> $e->getMessage(),
            ]));
        }

    }
    private function calculateAmountToRepay($amountRequested, $interestRate, $creditFeesAmount)
    {
        return $amountRequested + ($interestRate * $amountRequested) / 100 + $creditFeesAmount;
    }
    private function calculateDueDate($durationInDays)
    {
        $dateNow = Carbon::now();
        return $dateNow->addDays($durationInDays);
    }
    private function createRejectedRequestCredit(CreateRequestCredit $createRequestCredit)
    {
        try {
            $requestCredit = new RequestCredits();
            $requestCredit->id = Str::uuid();
            $requestCredit->amount_requested = $createRequestCredit->amount_requested;
            $requestCredit->product = $createRequestCredit->code ;
            $requestCredit->status = 'rejeté' ;
            $requestCredit->phoneNumber = $createRequestCredit->phoneNumber ;
            $requestCredit->save() ;
            return $requestCredit ;
        } catch (Exception $e) {
            throw new HttpResponseException(response()->json([
                'success'=> false,
                'status_code'=> 500,
                'error'=> true,
                'message'=> $e->getMessage(),
            ]));
        }
    }
    private function createMessage($requestCredit){
        if($requestCredit->status == 'accordé') {
            $formatDate  = $requestCredit->dueDate->format('d-m-Y') ;
            $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $formatDate. Le montant à rembourser est de $requestCredit->amount_to_repay CFA " ;
        } else {
            $message = "Cher client vous ne pouvez pas prendre de crédit parce que le montant $requestCredit->amount_requested CFA ne fait pas partir de la grille de ce produit " ;
        }
        return $message ;
    }
//    public function store(CreateRequestCredit $createRequestCredit)
//    {
//        $this->dateNow = Carbon::now();
//         if($createRequestCredit->code =="picoCredit") {
//
//                if($createRequestCredit->amount_requested >= 5000 and $createRequestCredit->amount_requested<=100000 ) {
//                    try {
//                        $this->creditFeesAmount = 500 ;
//                        $this->interestRate = 1.8 ;
//                        $this->durationInDays = 30 ;
//                        $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
//                        $this->dueDate = $this->dateNow->addDays($this->durationInDays);
//                        $this->dateFormat = $this->dueDate->format('d-m-Y');
//
//                        $requestCredits =  new RequestCredits();
//                        $requestCredits->id = Str::uuid() ;
//                        $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                        $requestCredits->product = $createRequestCredit->code ;
//                        $requestCredits->status = 'accordé' ;
//                        $requestCredits->dueDate = $this->dueDate ;
//                        $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                        $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
//                        $requestCredits->save() ;
//
//
//                        $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;
//
//                        return response()->json([
//                            'status_code' => 201,
//                            'message' => $message,
//                        ]);
//
//                    }catch (Exception $e) {
//                        throw new HttpResponseException(response()->json([
//                            'success'=> false,
//                            'status_code'=> 500,
//                            'error'=> true,
//                            'message'=> $e->getMessage(),
//                        ]));
//                    }
//                } else {
//
//                    try {
//                        $requestCredits =  new RequestCredits();
//                        $requestCredits->id = Str::uuid() ;
//                        $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                        $requestCredits->product = $createRequestCredit->code ;
//                        $requestCredits->status = 'rejeté' ;
//                        $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                        $requestCredits->save() ;
//
//                        $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
//                        return response()->json([
//                            'status_code' => 201,
//                            'message' => $message,
//                        ]);
//
//                    } catch (Exception $e) {
//                        throw new HttpResponseException(response()->json([
//                            'success'=> false,
//                            'status_code'=> 500,
//                            'error'=> true,
//                            'message'=> $e->getMessage(),
//                        ]));
//                    }
//
//                }
//         }
//
//        else if($createRequestCredit->code =="nanoCredit") {
//            if($createRequestCredit->amount_requested >= 100001 and $createRequestCredit->amount_requested<=300000 ) {
//                try {
//                    $this->creditFeesAmount = 1000 ;
//                    $this->interestRate = 6.2 ;
//                    $this->durationInDays = 90 ;
//                    $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
//                    $this->dueDate = $this->dateNow->addDays($this->durationInDays);
//                    $this->dateFormat = $this->dueDate->format('d-m-Y');
//
//                    $requestCredits =  new RequestCredits();
//                    $requestCredits->id = Str::uuid() ;
//                    $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                    $requestCredits->product = $createRequestCredit->code ;
//                    $requestCredits->status = 'accordé' ;
//                    $requestCredits->dueDate = $this->dueDate ;
//                    $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                    $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
//                    $requestCredits->save() ;
//
//
//                    $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;
//
//                    return response()->json([
//                        'status_code' => 201,
//                        'message' => $message,
//                    ]);
//
//                }catch (Exception $e) {
//                    throw new HttpResponseException(response()->json([
//                        'success'=> false,
//                        'status_code'=> 500,
//                        'error'=> true,
//                        'message'=> $e->getMessage(),
//                    ]));
//                }
//            } else {
//
//                try {
//                    $requestCredits =  new RequestCredits();
//                    $requestCredits->id = Str::uuid() ;
//                    $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                    $requestCredits->product = $createRequestCredit->code ;
//                    $requestCredits->status = 'rejeté' ;
//                    $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                    $requestCredits->save() ;
//
//                    $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
//                    return response()->json([
//                        'status_code' => 201,
//                        'message' => $message,
//                    ]);
//
//                } catch (Exception $e) {
//                    throw new HttpResponseException(response()->json([
//                        'success'=> false,
//                        'status_code'=> 500,
//                        'error'=> true,
//                        'message'=> $e->getMessage(),
//                    ]));
//                }
//
//            }
//        }
//
//       else if($createRequestCredit->code == "microCredit") {
//           if($createRequestCredit->amount_requested >= 300001 and $createRequestCredit->amount_requested<=500000 ) {
//               try {
//                   $this->creditFeesAmount = 5000 ;
//                   $this->interestRate = 6.2 ;
//                   $this->durationInDays = 90 ;
//                   $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
//                   $this->dueDate = $this->dateNow->addDays($this->durationInDays);
//                   $this->dateFormat = $this->dueDate->format('d-m-Y');
//
//                   $requestCredits =  new RequestCredits();
//                   $requestCredits->id = Str::uuid() ;
//                   $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                   $requestCredits->product = $createRequestCredit->code ;
//                   $requestCredits->status = 'accordé' ;
//                   $requestCredits->dueDate = $this->dueDate ;
//                   $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                   $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
//                   $requestCredits->save() ;
//
//
//                   $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;
//
//                   return response()->json([
//                       'status_code' => 201,
//                       'message' => $message,
//                   ]);
//
//               }catch (Exception $e) {
//                   throw new HttpResponseException(response()->json([
//                       'success'=> false,
//                       'status_code'=> 500,
//                       'error'=> true,
//                       'message'=> $e->getMessage(),
//                   ]));
//               }
//           } else {
//
//               try {
//                   $requestCredits =  new RequestCredits();
//                   $requestCredits->id = Str::uuid() ;
//                   $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
//                   $requestCredits->product = $createRequestCredit->code ;
//                   $requestCredits->status = 'rejeté' ;
//                   $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
//                   $requestCredits->save() ;
//
//                   $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
//                   return response()->json([
//                       'status_code' => 201,
//                       'message' => $message,
//                   ]);
//
//               } catch (Exception $e) {
//                   throw new HttpResponseException(response()->json([
//                       'success'=> false,
//                       'status_code'=> 500,
//                       'error'=> true,
//                       'message'=> $e->getMessage(),
//                   ]));
//               }
//
//           }
//        }
//       else {
//           throw new HttpResponseException(response()->json([
//               'success'=> false,
//               'status_code'=> 422,
//               'error'=> true,
//               'message'=> 'Erreur de Validation',
//               'errorList'=> ['Produit inexistant'],
//           ]));
//       }
//    }
//

    public function findAll() {
        try {
            return response()->json([
                'status_code' => 200,
                'data'=> RequestCredits::all()
            ]);
        } catch (Exception $e){
            return  response()->json($e);
        }
    }
}
