<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrayerTimeController extends Controller
{
    public function getPrayerTimes(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'year' => 'nullable|integer',
                'month' => 'nullable|integer|between:1,12',
            ]);

            $latitude = $request->latitude;
            $longitude = $request->longitude;
            
            // Build the URL with the required parameters
            $url = "https://api.waktusolat.app/v2/solat/gps/{$latitude}/{$longitude}";
            
            // Add optional parameters if provided
            if ($request->has('year')) {
                $url .= "?year=" . $request->year;
            }
            if ($request->has('month')) {
                $url .= (str_contains($url, '?') ? '&' : '?') . "month=" . $request->month;
            }

            Log::info('Fetching prayer times', [
                'url' => $url,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            $response = Http::get($url);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            if ($response->status() === 404) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prayer time data not found for the specified location'
                ], 404);
            }

            Log::error('Prayer times API error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch prayer times'
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Prayer times error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching prayer times'
            ], 500);
        }
    }
} 