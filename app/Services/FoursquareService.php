<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FoursquareService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.foursquare.com/v3/places/search';
    
    // Malaysian-specific category mappings
    protected $categoryMap = [
        'restaurant' => [
            '13000',  // Food category (parent)
            '13065',  // Restaurant
            '13002',  // Cafe
            '13035',  // Malaysian Restaurant
            '13145',  // Asian Restaurant
            '13003',  // Breakfast Spot
            '13040',  // Noodle House
            '13302',  // Hawker Stand
            '13334',  // Food Court
        ],
        'mosque' => [
            '12013',  // Religious Place
            '12094',  // Mosque
            '12095',  // Prayer Room
        ],
        'hotel' => [
            '19014',  // Hotel
            '19016',  // Hostel
            '19019',  // Resort
            '19021',  // Bed & Breakfast
            '19022',  // Boarding House
            '19023',  // Homestay
        ]
    ];
    
    // Malaysian-specific search terms
    protected $searchTerms = [
        'restaurant' => [
            'restaurant', 
            'kedai makan', 
            'restoran', 
            'cafe', 
            'warung', 
            'mamak', 
            'food court', 
            'hawker'
        ],
        'mosque' => [
            'mosque', 
            'masjid', 
            'surau', 
            'prayer room'
        ],
        'hotel' => [
            'hotel', 
            'homestay', 
            'penginapan', 
            'resort', 
            'chalet'
        ]
    ];

    public function __construct()
    {
        $this->apiKey = config('foursquare.api_key');
    }

    public function getNearbyPlaces(float $latitude, float $longitude, int $radius = 5000, string $type = null)
    {
        try {
            $results = [];
            
            // If type is specified, only search for that type
            $categoriesToSearch = $type ? [$type => $this->categoryMap[$type]] : $this->categoryMap;
            
            foreach ($categoriesToSearch as $placeType => $categoryIds) {
                $placesForType = [];
                
                // First, search by category IDs
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl, [
                    'll' => "{$latitude},{$longitude}",
                    'radius' => $radius,
                    'categories' => implode(',', $categoryIds),
                    'limit' => 20,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Foursquare category results for {$placeType}: " . count($data['results']));
                    
                    // Transform and add to our results
                    foreach ($data['results'] as $place) {
                        $placesForType[$place['fsq_id']] = $this->transformPlace($place, $placeType);
                    }
                } else {
                    Log::error("Error fetching {$placeType}s by category from Foursquare: " . $response->body());
                }
                
                // Then, search by local terms if needed
                if (count($placesForType) < 5 && isset($this->searchTerms[$placeType])) {
                    foreach ($this->searchTerms[$placeType] as $term) {
                        $response = Http::withHeaders([
                            'Authorization' => $this->apiKey,
                            'Accept' => 'application/json',
                        ])
                        ->get($this->baseUrl, [
                            'll' => "{$latitude},{$longitude}",
                            'radius' => $radius,
                            'query' => $term,
                            'limit' => 10,
                        ]);
                        
                        if ($response->successful()) {
                            $data = $response->json();
                            
                            // Transform and add to our results (avoiding duplicates)
                            foreach ($data['results'] as $place) {
                                if (!isset($placesForType[$place['fsq_id']])) {
                                    $placesForType[$place['fsq_id']] = $this->transformPlace($place, $placeType);
                                }
                            }
                        }
                    }
                }
                
                // Convert associative array back to indexed array
                $results[$placeType] = array_values($placesForType);
            }
            
            // If we don't have any results, try a general search
            $totalResults = array_sum(array_map(function($places) {
                return count($places);
            }, $results));
            
            if ($totalResults === 0) {
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl, [
                    'll' => "{$latitude},{$longitude}",
                    'radius' => $radius,
                    'limit' => 30,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Transform Foursquare results
                    $places = array_map(function($place) {
                        return $this->transformPlace($place, 'other');
                    }, $data['results']);
                    
                    $results['places'] = $places;
                }
            }
            
            return [
                'success' => true,
                'data' => $results
            ];
            
        } catch (\Exception $e) {
            Log::error('Foursquare Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while fetching nearby places',
                'error' => $e->getMessage()
            ];
        }
    }
    
    protected function transformPlace($place, $type)
    {
        return [
            'id' => $place['fsq_id'],
            'name' => $place['name'],
            'type' => $type,
            'address' => $place['location']['formatted_address'] ?? 
                         ($place['location']['address'] ?? ''),
            'coordinates' => [
                'latitude' => $place['geocodes']['main']['latitude'],
                'longitude' => $place['geocodes']['main']['longitude']
            ],
            'categories' => array_map(function($cat) {
                return [
                    'id' => $cat['id'],
                    'name' => $cat['name']
                ];
            }, $place['categories']),
            'distance' => $place['distance'] ?? null,
            'photo' => isset($place['photos'][0]) ? $place['photos'][0]['prefix'] . 'original' . $place['photos'][0]['suffix'] : null
        ];
    }
    
    public function getPlaceDetails(string $placeId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->get("https://api.foursquare.com/v3/places/{$placeId}");
            
            if (!$response->successful()) {
                Log::error('Foursquare API Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to fetch place details from Foursquare',
                    'status_code' => $response->status()
                ];
            }
            
            $place = $response->json();
            
            // Get photos for the place
            $photosResponse = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->get("https://api.foursquare.com/v3/places/{$placeId}/photos");
            
            $photos = [];
            if ($photosResponse->successful()) {
                $photosData = $photosResponse->json();
                $photos = array_map(function($photo) {
                    return $photo['prefix'] . 'original' . $photo['suffix'];
                }, $photosData);
            }
            
            $placeDetails = $this->transformPlace($place, '');
            $placeDetails['photos'] = $photos;
            
            return [
                'success' => true,
                'data' => $placeDetails
            ];
            
        } catch (\Exception $e) {
            Log::error('Foursquare Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while fetching place details',
                'error' => $e->getMessage()
            ];
        }
    }
} 