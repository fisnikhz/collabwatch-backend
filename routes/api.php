<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Friends\SendFriendRequestController;
use App\Http\Controllers\API\V1\VideoController;
use App\Http\Requests\API\V1\Friends\SendFriendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/videos', VideoController::class);
        Route::post('/friend-request', SendFriendRequestController::class);
    });

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test',App\Http\Controllers\API\V1\Feed\FeedController::class);
