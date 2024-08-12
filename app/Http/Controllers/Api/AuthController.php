<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\HttpErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(private readonly AuthService $authService)
    {
        //
    }

    /**
     * Login user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws HttpErrorResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->loginUser($request->validated());

        return (new LoginResource($user))
            ->additional([
                'success' => true,
                'message' => __('auth.logged_in'),
            ])
            ->response();
    }


    /**
     * Logout user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logoutUser();

        return $this->sendSuccessResponse(__('auth.logged_out'));
    }
}
