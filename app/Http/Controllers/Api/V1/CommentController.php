<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends BaseApiController
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    public function index(Request $request, Blog $blog): JsonResponse
    {
        Gate::authorize('viewAny', Comment::class);

        $comments = $this->commentService->list($blog, $request->all(), $request->user()?->id);

        return $this->paginatedResponse(
            paginator: $comments,
            resource: CommentResource::class,
            message: 'Comments fetched successfully'
        );
    }

    public function store(StoreCommentRequest $request, Blog $blog): JsonResponse
    {
        Gate::authorize('create', Comment::class);

        $this->commentService->create(
            $blog,
            $request->user()->id,
            $request->validated('description')
        );

        return $this->successResponse(
            message: 'Comment added successfully',
            statusCode: 201
        );
    }

    public function destroy(Request $request, Blog $blog, Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);

        $this->commentService->delete($comment);

        return $this->successResponse(message: 'Comment deleted successfully');
    }
}
