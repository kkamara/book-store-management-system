<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "rating" => $this->rating,
            "text" => $this->text,
            "user" => new UserResource($this->user),
            "createdAt" => $this->created_at,
            "updatedAt" => $this->created_at,
        ];
    }
}
