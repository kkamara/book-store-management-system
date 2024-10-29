<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderBookResource extends JsonResource
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
            "isbn13" => $this->isbn_13,
            "isbn10" => $this->isbn_10,
            "slug" => $this->slug,
            "name" => $this->name,
            "description" => $this->description,
            "jpgImageURL" => $this->jpg_image_url,
            "cost" => $this->formattedCost,
            "ratingAverage" => $this->rating_average,
            "binding" => $this->binding,
            "edition" => $this->edition,
            "author" => $this->author,
            "published" => $this->published,
            "publisher" => $this->publisher,
        ];
    }
}
