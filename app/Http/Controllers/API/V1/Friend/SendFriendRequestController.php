<?php

namespace App\Http\Controllers\API\V1\Friend;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Friends\SendFriendRequest;
use App\Http\Services\FriendService;
class SendFriendRequestController extends APIController
{
    protected $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    public function __invoke(SendFriendRequest $request)
    {
        return $this->friendService->sendRequest($request->friend_username);
    }
}
