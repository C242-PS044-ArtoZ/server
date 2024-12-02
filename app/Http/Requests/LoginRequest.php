<?php

  namespace App\Http\Requests;

  use App\Traits\FailedValidationTrait;
  use Illuminate\Foundation\Http\FormRequest;

  class LoginRequest extends FormRequest
  {
    use FailedValidationTrait;

    public function authorize(): bool
    {
      return true;
    }

    public function rules(): array
    {
      return [
        'email' => ['required', 'email:rfc,dns', 'exists:users,email', 'max:255'],
        'password' => [
          'required', 'string', 'min:8', 'max:64',
          'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/',
        ],
      ];
    }

    public function messages(): array
    {
      return [
        'email.required' => 'The email address field is required.',
        'email.email' => 'The email address must be valid and properly formatted.',
        'email.exists' => 'The email address does not match our records.',
        'email.max' => 'The email address must not exceed 255 characters.',
        'password.required' => 'The password field is required.',
        'password.string' => 'The password must be a valid string.',
        'password.min' => 'The password must be at least 8 characters long.',
        'password.max' => 'The password must not exceed 64 characters.',
        'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
      ];
    }
  }
