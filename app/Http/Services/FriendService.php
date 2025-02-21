<?php

namespace App\Http\Services;

use App\Enums\FriendStatusEnum;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class FriendService
{
    public function sendRequest(string $friendUsername): JsonResponse
    {
        $sender = Auth::user();
        $receiver = User::where('username', $friendUsername)->first();

        if (!$receiver) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($sender->id === $receiver->id) {
            return response()->json(['message' => 'You cannot send a friend request to yourself.'], 400);
        }

        $existingRequest = Friend::where(function ($query) use ($sender, $receiver) {
            $query->where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->orWhere('sender_id', $receiver->id)
                ->where('receiver_id', $sender->id);
        })->exists();

        if ($existingRequest) {
            return response()->json(['message' => 'Friend request already exists.'], 400);
        }

        $friendRequest = Friend::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => FriendStatusEnum::PENDING->value,
        ]);

        return response()->json(['message' => 'Friend request sent successfully.', 'friend_request' => $friendRequest], 201);
    }

    public function acceptRequest(Friend $friendRequest): JsonResponse
    {
        if ($friendRequest->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $friendRequest->update(['status' => FriendStatusEnum::ACCEPTED->value]);

        return response()->json(['message' => 'Friend request accepted.'], 200);
    }

    public function declineRequest(Friend $friendRequest): JsonResponse
    {
        if ($friendRequest->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $friendRequest->delete();

        return response()->json(['message' => 'Friend request declined.'], 200);
    }

    public function removeFriend(Friend $friend): JsonResponse
    {
        if ($friend->sender_id !== Auth::id() && $friend->receiver_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $friend->delete();

        return response()->json(['message' => 'Friend removed successfully.'], 200);
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

        return response()->json(['message' => 'User blocked successfully.'], 200);
    }
}
