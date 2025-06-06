<?php

namespace App\Http\Resources\V1;

use App\Http\Pagination\JSONStandardPaginatedResourceResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function toResponse($request)
    {
        return (new JSONStandardPaginatedResourceResponse($this))
            ->toResponse($request);
    }
}
