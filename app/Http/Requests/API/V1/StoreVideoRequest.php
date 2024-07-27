<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\APIRequest;

class StoreVideoRequest extends APIRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'video' => 'file|mimes:mp4,mov,avi,flv|max:50480',
        ];
    }
}
