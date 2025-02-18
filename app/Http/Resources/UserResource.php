<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Resources\Json\JsonResource;

  class UserResource extends JsonResource
  {
    public function toArray($request): array
    {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'created_at' => $this->formatted_created_at, // Menggunakan accessor
        'updated_at' => $this->formatted_updated_at, // Menggunakan accessor
        'deleted_at' => $this->formatted_deleted_at, // Menggunakan accessor (bila ada)
      ];
    }
  }
