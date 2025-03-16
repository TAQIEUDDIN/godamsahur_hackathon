<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceController extends Controller
{
    public function details($id)
    {
        try {
            // ... your existing place details code ...

            // Add prayer times for the location
            $prayerTimesResponse = Http::get("https://api.waktusolat.app/v2/solat/gps/{$place->latitude}/{$place->longitude}");
            
            if ($prayerTimesResponse->successful()) {
                $placeData['prayer_times'] = $prayerTimesResponse->json();
            }

            return response()->json([
                'success' => true,
                'data' => $placeData
            ]);

        } catch (\Exception $e) {
            Log::error('Place details error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch place details'
            ], 500);
        }
    }
} 