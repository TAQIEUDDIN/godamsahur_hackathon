<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'coordinates' => [
                'latitude' => $this->coordinates['latitude'],
                'longitude' => $this->coordinates['longitude'],
            ],
            'type' => $this->type,
        ];
    }
} 