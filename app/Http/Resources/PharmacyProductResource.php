<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'price' => (float) $this->pivot->price,
            'quantity' => $this->pivot->quantity,
            'created_at' => $this->pivot->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->pivot->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}