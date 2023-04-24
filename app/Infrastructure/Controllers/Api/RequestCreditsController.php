<?php

namespace App\Infrastructure\Controllers\Api;

use App\Application\Requests\CreateRequestCredits;
use App\Domain\Dto\CreateRequestCreditDto;
use App\Domain\Dto\RequestCreditDTO;
use App\Domain\Services\RequestCreditService;
use App\Http\Controllers\Controller;
class RequestCreditsController extends Controller
{
    private $requestCreditService;
    public function __construct(RequestCreditService $creditService)
    {
        $this->requestCreditService = $creditService ;
    }
    public function store(CreateRequestCredits $request)
    {
        $createRequestCreditDTO = new CreateRequestCreditDto(
            $request->input('phoneNumber'),
            $request->input('amount_requested'),
            $request->input('code'),
        );
        $requestCredit = $this->requestCreditService->create($createRequestCreditDTO);
        return response()->json([
            'status_code' => 201,
            'message' => $requestCredit,
        ]);
    }
    public function findAll() {

        $requestCredits = $this->requestCreditService->getAllCreditsRequest();

        $requestCreditDTOs = collect($requestCredits)->map(function($requestCredit) {
            return RequestCreditDTO::fromModel($requestCredit);
        })->all();

        return response()->json([
            'status_code' => 200,
            'data' => $requestCreditDTOs
        ]);
//        return response()->json([
//            'status_code' => 200,
//            'data'=> $this->requestCreditService->getAllCreditsRequest()
//        ]) ;
    }
}
