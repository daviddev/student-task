<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\TemplateResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    /**
     * Update user controller function.
     *
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        [$message, $user] = $this->userService->updateUser($request->validated());

        return (new TemplateResource($user))
            ->additional([
                'success' => true,
                'message' => $message,
            ])
            ->response();
    }
}
