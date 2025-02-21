<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Video\CommentRequest;
use App\Http\Services\CommentService;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class UpdateCommentVideoController extends APIController
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(CommentRequest $request, Comment $comment): JsonResponse
    {
        $comment = $this->commentService->updateComment($comment, $request->body);
        return $this->respondWithSuccess($comment);
    }
}
