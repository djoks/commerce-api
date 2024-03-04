<?php

namespace App\Traits;

use App\Models\ApiResponse;

/**
 * Api Responser Trait
 * 
 * This trait is utilized across controllers to standardize API responses sent to clients.
 * It defines a method for returning JSON responses with a consistent structure,
 * including a message, data payload, and HTTP status code.
 */
trait ApiResponser
{
    /**
     * Sends a JSON response to the client.
     * 
     * Constructs a JSON response object using the provided ApiResponse model,
     * which includes a message, an optional data payload, and an HTTP status code.
     * This method standardizes the API response format across the application.
     *
     * @param ApiResponse $response The ApiResponse model containing response details.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response for API calls.
     */
    protected function apiResponse(ApiResponse $response)
    {
        return response()->json([
            'message' => $response->message,
            'data' => $response->data,
        ], $response->code);
    }
}
