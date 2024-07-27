<?php
namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->attributes([
            'id',
            'uuid',
            'collection_name',
            'name',
            'file_name',
            'mime_type',
            'size',
            'order_column',
            'created_at',
            'updated_at',
            'thumbnail_url',
            'original_url',
        ])->data;
    }
}
