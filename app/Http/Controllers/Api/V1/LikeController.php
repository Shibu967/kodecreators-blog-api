<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Like\ToggleLikeRequest;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Like;
use App\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class LikeController extends BaseApiController
{
    public function __construct(
        private readonly LikeService $likeService
    ) {}

    public function toggle(ToggleLikeRequest $request): JsonResponse
    {
        Gate::authorize('create', Like::class);

        $type = $request->validated('type');
        $id = $request->validated('id');

        $model = $type === 'BLOG' 
            ? Blog::findOrFail($id) 
            : Comment::findOrFail($id);

        $isLiked = $this->likeService->toggleLike($model, $request->user()->id);
        $target = strtolower($type);

        return $this->successResponse(
            data: ['is_liked' => $isLiked],
            message: $isLiked ? ucfirst($target) . ' liked successfully' : ucfirst($target) . ' unliked successfully'
        );
    }
}

