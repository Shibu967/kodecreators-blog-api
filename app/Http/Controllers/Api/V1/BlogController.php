<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BlogController extends BaseApiController
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Blog::class);

        $blogs = $this->blogService->list($request->all(), $request->user()?->id);

        return $this->paginatedResponse(
            paginator: $blogs,
            resource: BlogResource::class,
            message: 'Blogs fetched successfully'
        );
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        Gate::authorize('create', Blog::class);

        $blog = $this->blogService->create(
            array_merge($request->validated(), ['user_id' => $request->user()->id]),
            $request->file('image')
        );

        $blog->load(['user', 'likes'])->loadCount(['comments', 'likes']);

        return $this->successResponse(
            data: new BlogResource($blog),
            message: 'Blog created successfully',
            statusCode: 201
        );
    }

    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        Gate::authorize('update', $blog);

        $updatedBlog = $this->blogService->update(
            $blog,
            $request->validated(),
            $request->file('image')
        );

        return $this->successResponse(
            data: new BlogResource($updatedBlog),
            message: 'Blog updated successfully'
        );
    }

    public function destroy(Request $request, Blog $blog): JsonResponse
    {
        Gate::authorize('delete', $blog);

        $this->blogService->delete($blog);

        return $this->successResponse(message: 'Blog deleted successfully');
    }
}
