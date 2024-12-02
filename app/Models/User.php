<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Concerns\HasUlids;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;
  use Illuminate\Support\Facades\Hash;
  use Laravel\Sanctum\HasApiTokens;

  class User extends Authenticatable
  {
    use HasApiTokens, HasFactory, Notifiable, HasUlids;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];

    public function setPasswordAttribute($value): void
    {
      $this->attributes['password'] = Hash::make($value);
    }

    public function setEmailAttribute($value): void
    {
      $this->attributes['email'] = strtolower($value);
    }

    public function getNameAttribute($value): string
    {
      return ucwords($value);
    }
  }
