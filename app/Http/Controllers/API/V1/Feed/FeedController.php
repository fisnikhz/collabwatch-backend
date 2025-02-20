<?php /** @noinspection PhpMissingParentConstructorInspection */

namespace App\Http\Controllers\API\V1\Feed;

use App\Http\Controllers\API\APIController;
use App\Http\Services\RecommendedVideosService;
use App\Http\Resources\API\V1\VideoResource;
use App\Models\Video;

class FeedController extends APIController
{
    protected $recommendedVideosService;

    public function __construct(RecommendedVideosService $recommendedVideosService)
    {
        $this->recommendedVideosService = $recommendedVideosService;
    }

    /**
     * @throws \Exception
     */
    public function __invoke()
    {
        $data = ($this->recommendedVideosService)(2);

        $keys = array_map(function ($item) {
            return array_key_first($item);
        }, $data);

        $dataVideosObj = Video::whereIn('id', $keys)->get();
        $serviceVideos = VideoResource::collection($dataVideosObj);

        $otherVideosObj = Video::whereNotIn('id', $keys)->orderBy('created_at', 'desc')->get();
        $otherVideos = VideoResource::collection($otherVideosObj);

        $response = $serviceVideos->merge($otherVideos);

        return $this->respondWithSuccess($response, __('app.success'));
    }
}
