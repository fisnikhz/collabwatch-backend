<?php

namespace App\Http\Controllers\API\V1\Friends;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Friends\SendFriendRequest;
use App\Http\Resources\API\V1\FriendResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SendFriendRequestController extends APIController
{
    public function __invoke(SendFriendRequest $request): \Illuminate\Http\JsonResponse
    {
        $sender = auth()->user();
        $friend = User::where('username',$request->input('friend_username'))->first();

        $receiverId = $friend->id;

        if ($sender->id == $receiverId) {
            return response()->json([
                'message' => 'You cannot send a friend request to yourself.'
            ], 400);
        }

        $existingRequest = Friend::where('sender_id', $sender->id)
            ->where('receiver_id', $receiverId)
            ->orWhere(function ($query) use ($sender, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $sender->id);
            })
            ->exists();

        if ($existingRequest) {
            return response()->json([
                'message' => 'A friend request already exists between these users.'
            ], 400);
        }

        $friendRequest = Friend::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId
        ]);

        return response()->json([
            'message' => 'Friend request sent successfully.',
            'friend_request' => new FriendResource($friendRequest)
        ], 201);
    }
}
