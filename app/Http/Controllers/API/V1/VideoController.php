<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreVideoRequest;
use App\Http\Resources\API\V1\VideoResource;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoController extends Controller
{
    public function __invoke(StoreVideoRequest $request): JsonResponse
    {

        $ffmpeg = FFMpeg::open($request->video);
        $duration = $ffmpeg->getDurationInSeconds();

        $midpoint  = intval($duration/2) ;
        $getThumbnail =$ffmpeg->getFrameFromSeconds($midpoint);
        $rand10 = Str::random(10);

        $path = $rand10.'thumbnail.jpg';
        $getThumbnail->export()->toDisk('public')->save($path);


        $org_path = Storage::url($path);
        $url = env('APP_URL') . $org_path;


        $video = Video::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('video')) {
            $media = $video->addMediaFromRequest('video')->toMediaCollection('videos');

            $media->update([
                'duration' => $duration,
                'thumbnail_url' => $url,
            ]);
        }

        $video->load('media');

        return response()->json([
            'message' => 'Video uploaded successfully',
            'video' => new VideoResource($video),
        ], 201);

    }
}
