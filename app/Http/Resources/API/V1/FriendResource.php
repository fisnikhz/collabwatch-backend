<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FriendResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = Auth::user();

        $friend = $this->sender_id === $authUser->id ? $this->receiver : $this->sender;

        return [
            'id' => $this->id,
            'friend' => new UserResource($friend),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
