<?php

namespace App\Http\Controllers\Api;

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





    public function store(CreateRequestCredit $createRequestCredit)
    {
        $this->dateNow = Carbon::now();
         if($createRequestCredit->code =="picoCredit") {

                if($createRequestCredit->amount_requested >= 5000 and $createRequestCredit->amount_requested<=100000 ) {
                    try {
                        $this->creditFeesAmount = 500 ;
                        $this->interestRate = 1.8 ;
                        $this->durationInDays = 30 ;
                        $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
                        $this->dueDate = $this->dateNow->addDays($this->durationInDays);
                        $this->dateFormat = $this->dueDate->format('d-m-Y');

                        $requestCredits =  new RequestCredits();
                        $requestCredits->id = Str::uuid() ;
                        $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                        $requestCredits->product = $createRequestCredit->code ;
                        $requestCredits->status = 'accordé' ;
                        $requestCredits->dueDate = $this->dueDate ;
                        $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                        $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
                        $requestCredits->save() ;


                        $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;

                        return response()->json([
                            'status_code' => 201,
                            'message' => $message,
                        ]);

                    }catch (Exception $e) {
                        throw new HttpResponseException(response()->json([
                            'success'=> false,
                            'status_code'=> 500,
                            'error'=> true,
                            'message'=> $e->getMessage(),
                        ]));
                    }
                } else {

                    try {
                        $requestCredits =  new RequestCredits();
                        $requestCredits->id = Str::uuid() ;
                        $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                        $requestCredits->product = $createRequestCredit->code ;
                        $requestCredits->status = 'rejeté' ;
                        $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                        $requestCredits->save() ;

                        $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
                        return response()->json([
                            'status_code' => 201,
                            'message' => $message,
                        ]);

                    } catch (Exception $e) {
                        throw new HttpResponseException(response()->json([
                            'success'=> false,
                            'status_code'=> 500,
                            'error'=> true,
                            'message'=> $e->getMessage(),
                        ]));
                    }

                }
         }

        else if($createRequestCredit->code =="nanoCredit") {
            if($createRequestCredit->amount_requested >= 100001 and $createRequestCredit->amount_requested<=300000 ) {
                try {
                    $this->creditFeesAmount = 1000 ;
                    $this->interestRate = 6.2 ;
                    $this->durationInDays = 90 ;
                    $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
                    $this->dueDate = $this->dateNow->addDays($this->durationInDays);
                    $this->dateFormat = $this->dueDate->format('d-m-Y');

                    $requestCredits =  new RequestCredits();
                    $requestCredits->id = Str::uuid() ;
                    $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                    $requestCredits->product = $createRequestCredit->code ;
                    $requestCredits->status = 'accordé' ;
                    $requestCredits->dueDate = $this->dueDate ;
                    $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                    $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
                    $requestCredits->save() ;


                    $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;

                    return response()->json([
                        'status_code' => 201,
                        'message' => $message,
                    ]);

                }catch (Exception $e) {
                    throw new HttpResponseException(response()->json([
                        'success'=> false,
                        'status_code'=> 500,
                        'error'=> true,
                        'message'=> $e->getMessage(),
                    ]));
                }
            } else {

                try {
                    $requestCredits =  new RequestCredits();
                    $requestCredits->id = Str::uuid() ;
                    $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                    $requestCredits->product = $createRequestCredit->code ;
                    $requestCredits->status = 'rejeté' ;
                    $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                    $requestCredits->save() ;

                    $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
                    return response()->json([
                        'status_code' => 201,
                        'message' => $message,
                    ]);

                } catch (Exception $e) {
                    throw new HttpResponseException(response()->json([
                        'success'=> false,
                        'status_code'=> 500,
                        'error'=> true,
                        'message'=> $e->getMessage(),
                    ]));
                }

            }
        }

       else if($createRequestCredit->code == "microCredit") {
           if($createRequestCredit->amount_requested >= 300001 and $createRequestCredit->amount_requested<=500000 ) {
               try {
                   $this->creditFeesAmount = 5000 ;
                   $this->interestRate = 6.2 ;
                   $this->durationInDays = 90 ;
                   $this->amount_to_repay = $createRequestCredit->amount_requested + ( $this->interestRate * $createRequestCredit->amount_requested ) / 100 + $this->creditFeesAmount ;
                   $this->dueDate = $this->dateNow->addDays($this->durationInDays);
                   $this->dateFormat = $this->dueDate->format('d-m-Y');

                   $requestCredits =  new RequestCredits();
                   $requestCredits->id = Str::uuid() ;
                   $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                   $requestCredits->product = $createRequestCredit->code ;
                   $requestCredits->status = 'accordé' ;
                   $requestCredits->dueDate = $this->dueDate ;
                   $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                   $requestCredits->amount_to_repay   =  $this->amount_to_repay ;
                   $requestCredits->save() ;


                   $message = "Cher client, votre crédit est accordé. Vous devez le rembourser au plus tard le $this->dateFormat. Le montant à rembourser est de $this->amount_to_repay CFA " ;

                   return response()->json([
                       'status_code' => 201,
                       'message' => $message,
                   ]);

               }catch (Exception $e) {
                   throw new HttpResponseException(response()->json([
                       'success'=> false,
                       'status_code'=> 500,
                       'error'=> true,
                       'message'=> $e->getMessage(),
                   ]));
               }
           } else {

               try {
                   $requestCredits =  new RequestCredits();
                   $requestCredits->id = Str::uuid() ;
                   $requestCredits->amount_requested = $createRequestCredit->amount_requested ;
                   $requestCredits->product = $createRequestCredit->code ;
                   $requestCredits->status = 'rejeté' ;
                   $requestCredits->phoneNumber = $createRequestCredit->phoneNumber ;
                   $requestCredits->save() ;

                   $message = "Cher client vous ne pouvez pas prendre ce crédit parce que le montant $requestCredits->amount_requested ne fait pas partir de la grille de ce produit " ;
                   return response()->json([
                       'status_code' => 201,
                       'message' => $message,
                   ]);

               } catch (Exception $e) {
                   throw new HttpResponseException(response()->json([
                       'success'=> false,
                       'status_code'=> 500,
                       'error'=> true,
                       'message'=> $e->getMessage(),
                   ]));
               }

           }
        }
       else {
           throw new HttpResponseException(response()->json([
               'success'=> false,
               'status_code'=> 422,
               'error'=> true,
               'message'=> 'Erreur de Validation',
               'errorList'=> ['Produit inexistant'],
           ]));
       }
    }


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
