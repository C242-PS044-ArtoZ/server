<?php

  namespace App\Models;

  use Carbon\Carbon;
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

    // Accessor for formatted created_at
    public function getFormattedCreatedAtAttribute(): string
    {
      return Carbon::parse($this->created_at)->format('d-m-Y H:i:s');
    }

    // Accessor for formatted updated_at
    public function getFormattedUpdatedAtAttribute(): string
    {
      return Carbon::parse($this->updated_at)->format('d-m-Y H:i:s');
    }

    // Accessor for formatted deleted_at
    public function getFormattedDeletedAtAttribute(): ?string
    {
      return $this->deleted_at ? Carbon::parse($this->deleted_at)->format('d-m-Y H:i:s') : null;
    }
  }
