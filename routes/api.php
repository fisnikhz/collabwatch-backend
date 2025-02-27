<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Feed\ExploreFeedController;
use App\Http\Controllers\API\V1\Feed\FeedController;
use App\Http\Controllers\API\V1\Friend\AcceptFriendRequestController;
use App\Http\Controllers\API\V1\Friend\BlockFriendController;
use App\Http\Controllers\API\V1\Friend\DeclineFriendRequestController;
use App\Http\Controllers\API\V1\Friend\GetFriendRequestsController;
use App\Http\Controllers\API\V1\Friend\GetFriendsController;
use App\Http\Controllers\API\V1\Friend\RemoveFriendController;
use App\Http\Controllers\API\V1\Friend\SendFriendRequestController;
use App\Http\Controllers\API\V1\User\FindUserByIdController;
use App\Http\Controllers\API\V1\User\FindUserController;
use App\Http\Controllers\API\V1\User\GetUserVideosController;
use App\Http\Controllers\API\V1\User\UpdateUserController;
use App\Http\Controllers\API\V1\User\UserController;
use App\Http\Controllers\API\V1\Video\CommentVideoController;
use App\Http\Controllers\API\V1\Video\DeleteCommentVideoController;
use App\Http\Controllers\API\V1\Video\GetVideoController;
use App\Http\Controllers\API\V1\Video\LikeVideoController;
use App\Http\Controllers\API\V1\Video\SaveVideoController;
use App\Http\Controllers\API\V1\Video\UpdateCommentVideoController;
use App\Http\Controllers\API\V1\Video\VideoController;
use App\Http\Controllers\API\V1\VideoTag\GetVideoTagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::get('/users', FindUserController::class);

    Route::middleware('auth:sanctum')->group(function () {
        //USERS
        Route::get('/users/{user}', FindUserByIdController::class);

        //AUTH_USER
        Route::get('/user/me', UserController::class);
        Route::post('/user/me/update', UpdateUserController::class);

        //VIDEO
        Route::post('/upload-video', VideoController::class);
        Route::get('/video/{video}',GetVideoController::class);
        Route::post('/video/{video}/like', LikeVideoController::class);
        Route::post('/video/{video}/save', SaveVideoController::class);
        Route::post('/video/{video}/comment', CommentVideoController::class);
        Route::get('users/{user}/videos', GetUserVideosController::class);

        //VIDEOTAG
        Route::get('/video-tags', GetVideoTagsController::class);

        //COMMENT
        Route::put('/comment/{comment}', UpdateCommentVideoController::class);
        Route::delete('/comment/{comment}', DeleteCommentVideoController::class);

        //FRIEND
        Route::get('/friends',GetFriendsController::class);
        Route::post('/friends/send', SendFriendRequestController::class);
        Route::post('/friends/accept/{friendRequest}', AcceptFriendRequestController::class);
        Route::post('/friends/decline/{friendRequest}', DeclineFriendRequestController::class);
        Route::delete('/friends/remove/{friend}', RemoveFriendController::class);
        Route::post('/friends/block/{user}', BlockFriendController::class);
        Route::get('/friends/requests', GetFriendRequestsController::class);

        //FEED
        Route::get('/explore-feed',ExploreFeedController::class);
        Route::get('/feed',FeedController::class);

    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

