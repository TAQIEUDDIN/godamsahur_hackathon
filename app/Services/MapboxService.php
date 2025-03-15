<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapboxService
{
    protected $accessToken;
    protected $baseUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/';

    public function __construct()
    {
        $this->accessToken = config('mapbox.access_token');
    }

    public function searchLocation(string $query, array $options = [])
    {
        try {
            $defaultOptions = [
                'access_token' => $this->accessToken,
                'country' => 'my', // Default to Malaysia
                'limit' => 5,
            ];

            $options = array_merge($defaultOptions, $options);
            
            $response = Http::get($this->baseUrl . urlencode($query) . '.json', $options);
            
            if (!$response->successful()) {
                Log::error('Mapbox API Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to fetch locations from Mapbox',
                    'status_code' => $response->status()
                ];
            }
            
            $data = $response->json();
            
            $locations = array_map(function($feature) {
                return [
                    'id' => $feature['id'],
                    'name' => $feature['text'],
                    'full_name' => $feature['place_name'],
                    'coordinates' => [
                        'longitude' => $feature['center'][0],
                        'latitude' => $feature['center'][1]
                    ],
                    'place_type' => $feature['place_type'][0] ?? null,
                    'context' => $feature['context'] ?? []
                ];
            }, $data['features']);
            
            return [
                'success' => true,
                'data' => $locations
            ];
            
        } catch (\Exception $e) {
            Log::error('Mapbox Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while searching for locations',
                'error' => $e->getMessage()
            ];
        }
    }
} 