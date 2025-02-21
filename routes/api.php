<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Friend\AcceptFriendRequestController;
use App\Http\Controllers\API\V1\Friend\BlockFriendController;
use App\Http\Controllers\API\V1\Friend\DeclineFriendRequestController;
use App\Http\Controllers\API\V1\Friend\RemoveFriendController;
use App\Http\Controllers\API\V1\Friend\SendFriendRequestController;
use App\Http\Controllers\API\V1\User\FindUserByIdController;
use App\Http\Controllers\API\V1\User\FindUserController;
use App\Http\Controllers\API\V1\User\UserController;
use App\Http\Controllers\API\V1\Video\CommentVideoController;
use App\Http\Controllers\API\V1\Video\DeleteCommentVideoController;
use App\Http\Controllers\API\V1\Video\LikeVideoController;
use App\Http\Controllers\API\V1\Video\SaveVideoController;
use App\Http\Controllers\API\V1\Video\UpdateCommentVideoController;
use App\Http\Controllers\API\V1\Video\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::get('/users', FindUserController::class);
    Route::get('/users/{user}', FindUserByIdController::class);
    Route::get('/test',App\Http\Controllers\API\V1\Feed\FeedController::class);


    Route::middleware('auth:sanctum')->group(function () {
        //AUTH_USER
        Route::get('/user/me', UserController::class);

        //VIDEO
        Route::post('/upload-video', VideoController::class);
        Route::post('/video/{video}/like', LikeVideoController::class);
        Route::post('/video/{video}/save', SaveVideoController::class);
        Route::post('/video/{video}/comment', CommentVideoController::class);

        //COMMENT
        Route::put('/comment/{comment}', UpdateCommentVideoController::class);
        Route::delete('/comment/{comment}', DeleteCommentVideoController::class);

        //FRIEND
        Route::post('/friends/send', SendFriendRequestController::class);
        Route::post('/friends/accept/{friendRequest}', AcceptFriendRequestController::class);
        Route::post('/friends/decline/{friendRequest}', DeclineFriendRequestController::class);
        Route::delete('/friends/remove/{friend}', RemoveFriendController::class);
        Route::post('/friends/block/{user}', BlockFriendController::class);

    });

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

