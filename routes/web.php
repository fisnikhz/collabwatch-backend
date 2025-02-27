<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    // User ID and number of recommendations
    $userId = 1;
    $numRecommendations = 5;

    // Prepare the data you want to send
    $data = [
        'user_id' => $userId,
        'num_recommendations' => $numRecommendations,
    ];

    // Make the POST request to the Python API
    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->post('http://127.0.0.1:5000/recommend', $data);

        // Check if the request was successful
        if ($response->successful()) {
            return $response->json(); // Returns the JSON response
        } else {
            return response()->json([
                'error' => 'Failed to get recommendations',
                'details' => $response->body(),
            ], $response->status());
        }
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json([
            'error' => 'An error occurred while fetching recommendations',
            'message' => $e->getMessage(),
        ], 500);
    }
});
