<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Video\CommentRequest;
use App\Http\Resources\API\V1\CommentResource;
use App\Http\Services\CommentService;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class CommentVideoController extends APIController
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(CommentRequest $request, Video $video): JsonResponse
    {
        $comment = $this->commentService->addComment($request->user(), $video, $request->body);
        return $this->respondWithSuccess(CommentResource::make($comment));
    }
}
