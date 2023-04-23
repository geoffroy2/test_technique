<?php

namespace App\Infrastructure\Controllers\Api;

use App\Application\Requests\CreateRequestCredits;
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
        $requestCredit = $this->requestCreditService->create($request);
        $message = $this->requestCreditService->createMessage($requestCredit);
        return response()->json([
            'status_code' => 201,
            'message' => $message,
        ]);
    }
    public function findAll() {
        return response()->json([
            'status_code' => 200,
            'data'=> $this->requestCreditService->getAllCreditsRequest()
        ]) ;
    }
}
