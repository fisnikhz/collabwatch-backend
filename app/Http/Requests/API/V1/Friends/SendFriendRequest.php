<?php

namespace App\Http\Requests\API\V1\Friends;

use App\Http\Requests\API\APIRequest;

class SendFriendRequest extends APIRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'friend_username' => 'required'
        ];
    }
}
