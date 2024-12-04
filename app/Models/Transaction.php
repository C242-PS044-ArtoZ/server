<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Concerns\HasUlids;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\SoftDeletes;

  class Transaction extends Model
  {
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
      'nominal',
      'type',
      'description',
      'user_id',
    ];

    protected $casts = [
      'nominal' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function setUserIdAttribute($value): void
    {
      $this->attributes['user_id'] = (string)$value;
    }
  }
