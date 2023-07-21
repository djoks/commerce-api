<?php

namespace App\Traits;

use App\Models\ApiResponse;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    protected function apiResponse(ApiResponse $response)
    {
        return response()->json([
            'message' => $response->message,
            'data' => $response->data,
        ], $response->code);
    }
}
