<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseApiRequest extends FormRequest
{
    use ApiResponseTrait;

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        $message = $validator->errors()->first();

        throw new HttpResponseException(
            $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
