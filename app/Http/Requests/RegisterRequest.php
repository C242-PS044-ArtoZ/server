<?php

  namespace App\Http\Requests;

  use App\Traits\FailedValidationTrait;
  use Illuminate\Foundation\Http\FormRequest;

  class RegisterRequest extends FormRequest
  {
    use FailedValidationTrait;

    public function authorize(): bool
    {
      return true;
    }

    public function rules(): array
    {
      return [
        'name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
        'email' => 'required|string|email:dns|max:255|unique:users,email',
        'password' => [
          'required', 'string', 'min:12', 'confirmed',
          'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/',
        ],
      ];
    }

    public function messages(): array
    {
      return [
        'name.required' => 'Name is required.',
        'name.regex' => 'Name can only contain letters and spaces.',
        'name.max' => 'Name cannot exceed 255 characters.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Email must be a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 12 characters.',
        'password.confirmed' => 'Passwords do not match.',
        'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
      ];
    }
  }
