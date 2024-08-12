<?php

namespace App\Repositories;

use App\Models\Session;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SessionRepository
{
    /**
     * Get sessions list repository function.
     *
     * @param array $data
     * @param bool|null $paginate
     * @return LengthAwarePaginator|Collection
     */
    public function listSessions(array $data, ?bool $paginate = true): LengthAwarePaginator|Collection
    {
        return Session::query()
            ->with('student:id,first_name,middle_name,last_name')
            ->when(
                $data['student_id'] ?? null,
                fn($q) => $q->whereStudentId($data['student_id'])
            )
            ->when(
                $data['start_time'] ?? null,
                fn($q) => $q->where('start_time', '>=', $data['start_time'])
            )
            ->when(
                $data['end_time'] ?? null,
                fn($q) => $q->where('end_time', '<=', $data['end_time'])
            )
            ->when(
                $paginate,
                fn($q) => $q->paginate($data['per_page'] ?? 20, ['*'], 'page', $data['page'] ?? 1),
                fn($q) => $q->get(),
            );
    }

    /**
     * Get notifiable sessions list repository function.
     *
     * @return Collection
     */
    public function listNotifiableSessions(): Collection
    {
        return Session::with('student')
            ->whereIsNotified(false)
            ->where('start_time', '>', now())
            ->where('start_time', '<=', now()->addMinutes(5))
            ->get();
    }

    /**
     * Store session repository function.
     *
     * @param array $data
     * @return Session
     */
    public function storeSession(array $data): Session
    {
        return Session::create($data);
    }

    /**
     * Update session repository function.
     *
     * @param Session $session
     * @param array $data
     * @return Session
     */
    public function updateSession(Session $session, array $data): Session
    {
        $session->update($data);
        $session->refresh();

        return $session;
    }

    /**
     * Check if sessions exists repository function.
     *
     * @param array $data
     * @return bool
     */
    public function sessionsExists(array $data): bool
    {
        return Session::where('start_time', '>', now())
            ->when(
                $data['type'] == Session::TYPE_REPEATED,
                fn($q) => $q->whereTime('start_time', '<=', $data['end_time'])
                    ->whereTime('end_time', '>=', $data['start_time']),
                fn($q) => $q->where(
                    fn($q) => $q->where(
                        fn($q) => $q->whereType(Session::TYPE_ONE_TIME)
                            ->where('start_time', '<=', $data['end_time'])
                            ->where('end_time', '>=', $data['start_time'])
                    )->orWhere(
                        fn($q) => $q->whereType(Session::TYPE_REPEATED)
                            ->whereTime('start_time', '<=', $data['end_time'])
                            ->whereTime('end_time', '>=', $data['start_time'])
                    )
                )
            )
            ->exists();
    }
}
