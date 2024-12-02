<?php

  namespace App\Traits;

  use App\Helpers\ApiResponse;
  use Illuminate\Contracts\Validation\Validator;
  use Illuminate\Http\Exceptions\HttpResponseException;

  trait FailedValidationTrait
  {
    protected function failedValidation(Validator $validator): void
    {
      throw new HttpResponseException(ApiResponse::error('Invalid data submitted.', 422, $validator->errors()->toArray()));
    }
  }
