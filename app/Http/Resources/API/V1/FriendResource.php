<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->attributes([
            'id',
            'sender_id'=>$this->whenLoaded('sender'),
            'receiver_id' =>$this->whenLoaded('receiver'),
            'status',
            'created_at',
            'updated_at'
        ])->data;
    }
}
