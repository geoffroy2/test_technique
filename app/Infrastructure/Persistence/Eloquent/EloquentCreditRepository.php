<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Requests\CreateRequestCredits;
use App\Domain\Dto\RequestCreditDTO;
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
        $requestCredits = $this->model->all();
        return $requestCredits->map(function($requestCredit) {
            return RequestCreditDTO::fromModel($requestCredit);
        })->all();

    }

}
