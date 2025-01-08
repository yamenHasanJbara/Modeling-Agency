<?php


namespace App\Http\Traits;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait JsonValidationErrors
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()
                ->json(
                    [
                        'success'=>false,
                        'data'=>[],
                        'errors'=>$validator->errors()
                    ]
                    , Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
