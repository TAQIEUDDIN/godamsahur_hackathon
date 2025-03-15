<?php
// app/Http/Controllers/PlaceController.php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceController extends Controller
{
    private $placeCategories = [
        'restaurant' => 'restaurant',
        'mosque' => 'place_of_worship',
        'hotel' => 'lodging'
    ];

    public function index()
    {
        return view('index');
    }
    
    public function search(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $type = $request->input('type');
        $radius = $request->input('radius', 5); // Default radius in kilometers
        
        // Calculate the approximate bounds for the search radius
        // Each degree of latitude is approximately 111km
        $latDelta = $radius / 111;
        $lngDelta = $radius / (111 * cos(deg2rad($latitude)));
        
        $query = Place::query();
        
        // Filter by distance
        $query->whereBetween('latitude', [$latitude - $latDelta, $latitude + $latDelta])
              ->whereBetween('longitude', [$longitude - $lngDelta, $longitude + $lngDelta]);
        
        // Filter by type if specified
        if ($type) {
            $query->where('type', $type);
        }
        
        $places = $query->get();
        
        // Calculate exact distance for each place using Haversine formula
        $places = $places->map(function ($place) use ($latitude, $longitude) {
            $place->distance = $this->calculateDistance(
                $latitude, 
                $longitude, 
                $place->latitude, 
                $place->longitude
            );
            return $place;
        })->filter(function ($place) use ($radius) {
            return $place->distance <= $radius;
        })->sortBy('distance');
        
        return response()->json($places);
    }
    
    // Haversine formula to calculate distance between two coordinates in kilometers
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
    // Method to fetch places from Foursquare API with Malaysian context
    public function fetchFromMapbox(Request $request)
    {
        try {
            $query = $request->input('query');
            $mapboxToken = config('mapbox.access_token');
            $foursquareKey = config('foursquare.api_key');

            // Add detailed logging
            Log::info('Starting search with parameters:', [
                'query' => $query,
                'mapbox_token_exists' => !empty($mapboxToken),
                'foursquare_key_exists' => !empty($foursquareKey)
            ]);

            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required'
                ], 400);
            }

            // First, get the location coordinates using Mapbox
            $locationResponse = Http::get('https://api.mapbox.com/geocoding/v5/mapbox.places/'. urlencode($query) .'.json', [
                'access_token' => $mapboxToken,
                'country' => 'my',
                'limit' => 1,
            ]);

            Log::info('Mapbox API Response:', [
                'status' => $locationResponse->status(),
                'body' => $locationResponse->json()
            ]);

            if (!$locationResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch location from Mapbox',
                    'error' => $locationResponse->body()
                ], $locationResponse->status());
            }

            $locationData = $locationResponse->json();
            $mainLocation = $locationData['features'][0] ?? null;
            
            if (!$mainLocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found. Please try a different search term.'
                ], 404);
            }
            
            // Get the coordinates for Foursquare search
            $coordinates = $mainLocation['center'] ?? null;
            
            if (!$coordinates) {
                return response()->json([
                    'error' => 'Could not determine location coordinates.'
                ], 400);
            }
            
            // Malaysian-specific category mappings
            // These are the Foursquare category IDs that match our needs
            $categoryMap = [
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
            $searchTerms = [
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
            
            $results = [];
            
            // Search for each category using Foursquare
            foreach ($categoryMap as $type => $categoryIds) {
                $placesForType = [];
                
                // First, search by category IDs
                $response = Http::withHeaders([
                    'Authorization' => $foursquareKey,
                    'Accept' => 'application/json',
                ])
                ->get('https://api.foursquare.com/v3/places/search', [
                    'll' => "{$coordinates[1]},{$coordinates[0]}", // Foursquare uses lat,lng format
                    'radius' => 5000, // 5km radius
                    'categories' => implode(',', $categoryIds),
                    'limit' => 20,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Foursquare category results for {$type}: " . count($data['results']));
                    
                    // Transform and add to our results
                    foreach ($data['results'] as $place) {
                        $placesForType[$place['fsq_id']] = [
                            'id' => $place['fsq_id'],
                            'text' => $place['name'],
                            'place_name' => isset($place['location']['formatted_address']) ? 
                                $place['location']['formatted_address'] : 
                                ($place['location']['address'] ?? ''),
                            'center' => [
                                $place['geocodes']['main']['longitude'],
                                $place['geocodes']['main']['latitude']
                            ],
                            'properties' => [
                                'address' => $place['location']['address'] ?? '',
                                'categories' => array_map(function($cat) {
                                    return $cat['name'];
                                }, $place['categories'])
                            ]
                        ];
                    }
                } else {
                    Log::error("Error fetching {$type}s by category from Foursquare: " . $response->body());
                }
                
                // Then, search by local terms
                foreach ($searchTerms[$type] as $term) {
                    $searchQuery = $term . ' in ' . $query;
                    
                    $response = Http::withHeaders([
                        'Authorization' => $foursquareKey,
                        'Accept' => 'application/json',
                    ])
                    ->get('https://api.foursquare.com/v3/places/search', [
                        'll' => "{$coordinates[1]},{$coordinates[0]}", // Foursquare uses lat,lng format
                        'radius' => 5000, // 5km radius
                        'query' => $term,
                        'limit' => 10,
                    ]);
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        Log::info("Foursquare term results for {$term}: " . count($data['results']));
                        
                        // Transform and add to our results (avoiding duplicates)
                        foreach ($data['results'] as $place) {
                            if (!isset($placesForType[$place['fsq_id']])) {
                                $placesForType[$place['fsq_id']] = [
                                    'id' => $place['fsq_id'],
                                    'text' => $place['name'],
                                    'place_name' => isset($place['location']['formatted_address']) ? 
                                        $place['location']['formatted_address'] : 
                                        ($place['location']['address'] ?? ''),
                                    'center' => [
                                        $place['geocodes']['main']['longitude'],
                                        $place['geocodes']['main']['latitude']
                                    ],
                                    'properties' => [
                                        'address' => $place['location']['address'] ?? '',
                                        'categories' => array_map(function($cat) {
                                            return $cat['name'];
                                        }, $place['categories'])
                                    ]
                                ];
                            }
                        }
                    } else {
                        Log::error("Error fetching {$term} from Foursquare: " . $response->body());
                    }
                }
                
                // Convert associative array back to indexed array
                $results[$type] = array_values($placesForType);
                Log::info("Total unique places for {$type}: " . count($results[$type]));
            }
            
            // If we don't have any results, try a general search
            $totalResults = array_sum(array_map(function($places) {
                return count($places);
            }, $results));
            
            if ($totalResults === 0) {
                $response = Http::withHeaders([
                    'Authorization' => $foursquareKey,
                    'Accept' => 'application/json',
                ])
                ->get('https://api.foursquare.com/v3/places/search', [
                    'll' => "{$coordinates[1]},{$coordinates[0]}", // Foursquare uses lat,lng format
                    'radius' => 5000, // 5km radius
                    'limit' => 30,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("General Foursquare results: " . count($data['results']));
                    
                    // Transform Foursquare results
                    $places = array_map(function($place) {
                        return [
                            'id' => $place['fsq_id'],
                            'text' => $place['name'],
                            'place_name' => isset($place['location']['formatted_address']) ? 
                                $place['location']['formatted_address'] : 
                                ($place['location']['address'] ?? ''),
                            'center' => [
                                $place['geocodes']['main']['longitude'],
                                $place['geocodes']['main']['latitude']
                            ],
                            'properties' => [
                                'address' => $place['location']['address'] ?? '',
                                'categories' => array_map(function($cat) {
                                    return $cat['name'];
                                }, $place['categories'])
                            ]
                        ];
                    }, $data['results']);
                    
                    $results['places'] = $places;
                }
            }

            return response()->json([
                'success' => true,
                'location' => $mainLocation,
                'places' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Search API Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'query' => $request->input('query')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function categorizePlace($place)
    {
        $categories = $place['properties']['category'] ?? '';
        $categories = strtolower($categories);
        
        if (strpos($categories, 'restaurant') !== false || 
            strpos($categories, 'food') !== false || 
            strpos($categories, 'cafe') !== false) {
            return 'restaurant';
        }
        
        if (strpos($categories, 'mosque') !== false || 
            strpos($categories, 'worship') !== false || 
            strpos($categories, 'masjid') !== false) {
            return 'mosque';
        }
        
        if (strpos($categories, 'hotel') !== false || 
            strpos($categories, 'lodging') !== false || 
            strpos($categories, 'accommodation') !== false) {
            return 'hotel';
        }
        
        return null;
    }

    public function searchPlaces(Request $request)
    {
        try {
            $query = $request->input('query');
            $accessToken = config('mapbox.access_token');

            if (empty($query)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Search query is required'
                ], 400);
            }

            // Search in Malaysia
            $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$query}.json", [
                'access_token' => $accessToken,
                'country' => 'my',
                'types' => 'poi,place',
                'limit' => 10
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to fetch places'
                ], $response->status());
            }

            $data = $response->json();
            $places = array_map(function($feature) {
                return [
                    'id' => $feature['id'],
                    'name' => $feature['text'],
                    'address' => $feature['place_name'],
                    'location' => [
                        'latitude' => $feature['center'][1],
                        'longitude' => $feature['center'][0]
                    ]
                ];
            }, $data['features']);

            return response()->json([
                'status' => true,
                'data' => $places
            ]);

        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    public function searchByType(Request $request, $type)
    {
        try {
            $query = $request->input('query');
            $accessToken = config('mapbox.access_token');

            if (empty($query)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Search query is required'
                ], 400);
            }

            $searchQuery = "{$type} in {$query}";
            
            $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$searchQuery}.json", [
                'access_token' => $accessToken,
                'country' => 'my',
                'types' => 'poi',
                'limit' => 10
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to fetch places'
                ], $response->status());
            }

            $data = $response->json();
            $places = array_map(function($feature) use ($type) {
                return [
                    'id' => $feature['id'],
                    'name' => $feature['text'],
                    'type' => $type,
                    'address' => $feature['place_name'],
                    'location' => [
                        'latitude' => $feature['center'][1],
                        'longitude' => $feature['center'][0]
                    ]
                ];
            }, $data['features']);

            return response()->json([
                'status' => true,
                'data' => $places
            ]);

        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }
}