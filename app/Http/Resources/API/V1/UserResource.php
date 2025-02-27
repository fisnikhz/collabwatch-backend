<?php

namespace App\Http\Resources\API\V1;

use App\Enums\FriendStatusEnum;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'user_id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'bio' => $this->bio,
            'avatar_url' => $this->getFirstMediaUrl('avatar'),
            'friends_count' => $this->friends()->count(),
            'videos_count' => $this->videos()->count(),
            'likes_count' => $this->likedVideos()->count(),
            'saves_count' => $this->savedVideos()->count(),
            'friend_status' => $this->getFriendStatus(),
            'friend_id' => $this->getFriendId(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function query($currentUser){
        return Friend::where(function ($query) use ($currentUser) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $this->id);
        })
            ->orWhere(function ($query) use ($currentUser) {
                $query->where('sender_id', $this->id)
                    ->where('receiver_id', $currentUser->id);
            })
            ->first();
    }
    protected function getFriendStatus()
    {
        $currentUser = auth()?->user();

        if($currentUser === null) {
            return null;
        }

        $friendship = $this->query($currentUser);

        return $friendship ? $friendship->status : FriendStatusEnum::NONE;
    }

    protected function getFriendId()
    {
        $currentUser = auth()?->user();

        if($currentUser === null) {
            return null;
        }

        $friendship = $this->query($currentUser);

        return $friendship ? $friendship->id : null;
    }

}
