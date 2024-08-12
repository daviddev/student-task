<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

readonly class UserService
{
    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(private UserRepository $userRepository)
    {
        //
    }

    /**
     * Update user service function.
     *
     * @param array $data
     * @return array
     */
    public function updateUser(array $data): array
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        $message = $authUser->report_template ?
            __('response.report_template.updated') :
            __('response.report_template.created');

        return [
            $message,
            $this->userRepository->updateUser($authUser, $data),
        ];
    }
}
