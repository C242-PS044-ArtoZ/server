<?php

  namespace App\Http\Requests;

  use App\Traits\FailedValidationTrait;
  use Illuminate\Foundation\Http\FormRequest;

  class LoginRequest extends FormRequest
  {
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      return true; // Allow all users to attempt login
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
      return [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8',
      ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
      return [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please provide a valid email address.',
        'email.exists' => 'This email address is not registered.',
        'password.required' => 'Password is required.',
        'password.string' => 'Password must be a valid string.',
        'password.min' => 'Password must be at least 8 characters long.',
      ];
    }
  }
