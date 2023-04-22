<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequestCredit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount_requested' => 'required',
            'phoneNumber' => 'required|regex:/^[0-9]{10}$/',
            'code' => 'required',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'=> false,
            'status_code'=> 422,
            'error'=> true,
            'message'=> 'Erreur de Validation',
            'errorList'=> $validator->errors(),
        ]));
    }

    public function messages()
    {
        return [
            'phoneNumber.required' => 'Le numero de téléphone est Obligatoire',
            'amount_requested.required' => 'Le montant est Obligatoire',
            'phoneNumber.regex' => 'Le format du numero incorrect',
            'code.required' => 'Le choix du produit est obligatoire',
        ];
    }
}
