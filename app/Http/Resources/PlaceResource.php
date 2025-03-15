<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'coordinates' => [
                'latitude' => $this->coordinates['latitude'],
                'longitude' => $this->coordinates['longitude'],
            ],
            'address' => $this->address,
            'categories' => $this->categories,
            'distance' => $this->distance,
            'details' => $this->when($this->details, [
                'tel' => $this->details['tel'] ?? null,
                'website' => $this->details['website'] ?? null,
                'hours' => $this->details['hours'] ?? null,
                'rating' => $this->details['rating'] ?? null,
                'photos' => $this->details['photos'] ?? [],
            ]),
        ];
    }
} 