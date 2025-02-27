<?php

namespace App\Http\Services;

use App\Enums\FriendStatusEnum;
use App\Http\Resources\API\V1\FriendResource;
use App\Models\Friend;
use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class FriendService
{
    use APIResponse;
    public function sendRequest(string $friendUsername): JsonResponse
    {
        $sender = Auth::user();
        $receiver = User::where('username', $friendUsername)->first();

        if (!$receiver) {
            return $this->respondWithError('User not found.', 404);
        }

        if ($sender->id === $receiver->id) {
            return $this->respondWithError('You cannot send a friend request to yourself.');
        }

        $existingRequest = Friend::where(function ($query) use ($sender, $receiver) {
            $query->where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->orWhere('sender_id', $receiver->id)
                ->where('receiver_id', $sender->id);
        })->exists();

        if ($existingRequest) {
            return $this->respondWithError('Friend request already exists.');
        }

        $friendRequest = Friend::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => FriendStatusEnum::PENDING->value,
        ]);

        return $this->respondWithSuccess('Friend request sent successfully.', ['friend_request' => $friendRequest], 201);
    }

    public function acceptRequest(Friend $friendRequest): JsonResponse
    {
        if ($friendRequest->receiver_id !== Auth::id()) {
            return $this->respondWithError('Unauthorized action.', 403);
        }

        $friendRequest->update(['status' => FriendStatusEnum::ACCEPTED->value]);

        return $this->respondWithSuccess('Friend request accepted.');
    }

    public function declineRequest(Friend $friendRequest): JsonResponse
    {
        if ($friendRequest->receiver_id !== Auth::id()) {
            return $this->respondWithError('Unauthorized action.', 403);
        }

        $friendRequest->delete();

        return $this->respondWithSuccess('Friend request declined.');
    }

    public function removeFriend(Friend $friend): JsonResponse
    {
        if ($friend->sender_id !== Auth::id() && $friend->receiver_id !== Auth::id()) {
            return $this->respondWithError('Unauthorized action.', 403);
        }

        $friend->delete();

        return $this->respondWithSuccess('Friend removed successfully.');
    }

    public function blockUser(User $user): JsonResponse
    {
        $authUser = Auth::user();

        Friend::where(function ($query) use ($authUser, $user) {
            $query->where('sender_id', $authUser->id)
                ->where('receiver_id', $user->id)
                ->orWhere('sender_id', $user->id)
                ->where('receiver_id', $authUser->id);
        })->delete();

        Friend::create([
            'sender_id' => $authUser->id,
            'receiver_id' => $user->id,
            'status' => FriendStatusEnum::BLOCKED->value,
        ]);

        return $this->respondWithSuccess('User blocked successfully.');
    }

    public function getFriends(): JsonResponse
    {
        $authUser = Auth::user();

        $friends = Friend::where(function ($query) use ($authUser) {
            $query->where('sender_id', $authUser->id)
                ->where('status', FriendStatusEnum::ACCEPTED->value)
                ->orWhere('receiver_id', $authUser->id)
                ->where('status', FriendStatusEnum::ACCEPTED->value);
        })->with([
            'sender' => function ($query) {
                $query->withCount(['friends', 'videos', 'likedVideos']);
            },
            'receiver' => function ($query) {
                $query->withCount(['friends', 'videos', 'likedVideos']);
            }
        ])->get();

        return $this->respondWithSuccess([
            'friends' => FriendResource::collection($friends),
        ]);
    }

    public function getFriendRequests()
    {
        $authUser = Auth::user();

        return Friend::where(function ($query) use ($authUser) {
            $query->where('receiver_id', $authUser->id);
        })->where('status', FriendStatusEnum::PENDING->value)->get();
    }

}
