<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateSelfRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends BaseApiController
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function store(CreateUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Gate::authorize('create', [User::class, UserRole::from($validated['role'])]);

        $user = $this->userService->createUser($validated);

        return $this->successResponse(
            data: new UserResource($user),
            message: 'User created successfully',
            statusCode: 201
        );
    }

    public function updateSelf(UpdateSelfRequest $request): JsonResponse
    {
        $user = $request->user();

        Gate::authorize('update', $user);

        $updatedUser = $this->userService->updateName($user, $request->validated('name'));

        return $this->successResponse(
            data: new UserResource($updatedUser),
            message: 'Profile updated successfully'
        );
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: new UserResource($request->user()),
            message: 'Profile fetched successfully'
        );
    }
}
