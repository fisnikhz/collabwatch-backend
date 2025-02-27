<?php /** @noinspection PhpMissingParentConstructorInspection */

namespace App\Http\Controllers\API\V1\Feed;

use App\Http\Controllers\API\APIController;
use App\Http\Services\FeedService;
use App\Http\Services\RecommendedVideosService;
use App\Http\Resources\API\V1\VideoResource;
use App\Models\Video;

class FeedController extends APIController
{
    protected RecommendedVideosService $feedService;

    public function __construct(RecommendedVideosService $feedService)
    {
        $this->feedService = $feedService;
    }

    public function __invoke()
    {
        $videoIds = $this->feedService->getPersonalizedFeed(auth()->user()->id);

        $videos = Video::whereIn('id', $videoIds)->paginate();

        return $this->respondWithSuccess(VideoResource::collection($videos), __('app.success'));
    }
}
