<?php

namespace App\Services;

use App\Exceptions\HttpErrorResponse;
use App\Models\Session;
use App\Repositories\SessionRepository;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

readonly class SessionService
{
    /**
     * SessionService constructor.
     *
     * @param SessionRepository $sessionRepository
     * @param StudentRepository $studentRepository
     */
    public function __construct(
        private SessionRepository $sessionRepository,
        private StudentRepository $studentRepository
    )
    {
        //
    }

    /**
     * Get sessions list service function.
     *
     * @param $data
     * @return LengthAwarePaginator
     */
    public function listSessions($data): LengthAwarePaginator
    {
        return $this->sessionRepository->listSessions($data);
    }

    /**
     * Store session service function.
     *
     * @param array $data
     * @return Session
     * @throws HttpErrorResponse
     */
    public function storeSession(array $data): Session
    {
        $data['start_time'] = Carbon::parse($data['date']);
        $data['end_time'] = $data['start_time']->clone()->addMinutes($data['duration']);
        unset($data['date'], $data['duration']);

        $student = $this->studentRepository->getStudentBiId($data['student_id']);

        if ($data['type'] === Session::TYPE_REPEATED) {
            foreach ($student->availability as $value) {
                if (!$value) {
                    throw new HttpErrorResponse(__('validation.week_availability'), [], 422);
                }
            }
        } elseif (!$student->availability[$data['start_time']->format('l')]) {
            throw new HttpErrorResponse(
                __('validation.week_day_availability', ['week' => $data['start_time']->format('l')]),
                [],
                422
            );
        }
        if ($this->sessionRepository->sessionsExists(Arr::only($data, ['start_time', 'end_time', 'type']))) {
            throw new HttpErrorResponse(__('validation.session_exists'), [], 422);
        }

        return $this->sessionRepository->storeSession($data);
    }

    /**
     * Update session service function.
     *
     * @param Session $session
     * @param array $data
     * @return Session
     */
    public function updateSession(Session $session, array $data): Session
    {
        return $this->sessionRepository->updateSession($session, $data);
    }
}
