<?php

  namespace App\Traits;

  use App\Helpers\ApiResponse;
  use Illuminate\Contracts\Validation\Validator;
  use Illuminate\Http\Exceptions\HttpResponseException;

  trait FailedValidationTrait
  {
    protected function failedValidation(Validator $validator): void
    {
      throw new HttpResponseException(
        ApiResponse::error(
          'Validation failed. Please check the provided data and try again.',
          422,
          $validator->errors()->toArray()
        )
      );
    }
  }
