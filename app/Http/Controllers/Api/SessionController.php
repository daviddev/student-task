<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\HttpErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Session\GetSessionsRequest;
use App\Http\Requests\Session\StoreSessionRequest;
use App\Http\Requests\Session\UpdateSessionRequest;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * SessionController constructor.
     *
     * @param SessionService $sessionService
     */
    public function __construct(private readonly SessionService $sessionService)
    {
        //
    }

    /**
     * Get sessions list controller function.
     *
     * @param GetSessionsRequest $request
     * @return JsonResponse
     */
    public function index(GetSessionsRequest $request): JsonResponse
    {
        $sessions = $this->sessionService->listSessions($request->validated());

        return SessionResource::collection($sessions->items())
            ->additional([
                'success' => true,
                'total' => $sessions->total(),
                'page' => $sessions->currentPage(),
                'per_page' => $sessions->perPage(),
                'last_page' => $sessions->lastPage(),
            ])
            ->response();
    }

    /**
     * Store session controller function.
     *
     * @param StoreSessionRequest $request
     * @return JsonResponse
     * @throws HttpErrorResponse
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        $session = $this->sessionService->storeSession($request->validated());

        return (new SessionResource($session))
            ->additional([
                'success' => true,
                'message' => __('response.session.created'),
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update session controller function.
     *
     * @param UpdateSessionRequest $request
     * @param Session $session
     * @return JsonResponse
     */
    public function update(UpdateSessionRequest $request, Session $session): JsonResponse
    {
        if (!auth()->user()->can('update', $session)) {
            return $this->sendErrorResponse(__('validation.rate_session'), [], 403);
        }
        $session = $this->sessionService->updateSession($session, $request->validated());

        return (new SessionResource($session))
            ->additional([
                'success' => true,
                'message' => __('response.session.updated'),
            ])
            ->response();
    }
}
