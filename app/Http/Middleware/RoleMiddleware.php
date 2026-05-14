<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return $this->errorResponse('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
        }

        $userRole = $user->role?->value ?? $user->role;

        if (! in_array($userRole, $roles, true)) {
            return $this->errorResponse('Unauthorized access.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
