<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use Illuminate\Http\Request;

class UserController extends APIController
{

    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->respondWithSuccess($request->user()->load(['media','videos.media','videos.likes','videos.saves']));
    }

}
