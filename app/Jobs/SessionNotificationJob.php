<?php

namespace App\Jobs;

use App\Models\Session;
use App\Notifications\SessionNotification;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SessionNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SessionRepository $sessionRepository, UserRepository $userRepository): void
    {
        $sessions = $sessionRepository->listNotifiableSessions();
        $user = $userRepository->getUserById(1);

        /** @var Session $session */
        foreach ($sessions as $session) {
            $session->student->notify(new SessionNotification($session, $user, 'notifications.student_session'));
            $user->notify(new SessionNotification($session, $user, 'notifications.user_session'));
            $sessionRepository->updateSession($session, ['is_notified' => true]);
        }
    }
}
