<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Resources\Json\JsonResource;

  class UserResource extends JsonResource
  {
    public function toArray($request): array
    {
      return $this->only(['id', 'name', 'email', 'created_at', 'updated_at']);
    }
  }
