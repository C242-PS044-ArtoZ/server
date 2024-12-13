<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Resources\Json\JsonResource;

  class TransactionResource extends JsonResource
  {
    public function toArray($request): array
    {
      return [
        'id' => $this->id,
        'nominal' => $this->nominal,
        'type' => $this->type,
        'description' => $this->description,
        'user_id' => $this->user_id,
        'created_at' => $this->formatted_created_at, // Menggunakan accessor
        'updated_at' => $this->formatted_updated_at, // Menggunakan accessor
        'deleted_at' => $this->formatted_deleted_at, // Menggunakan accessor (bila ada)
      ];
    }
  }
