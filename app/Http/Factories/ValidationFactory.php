<?php

namespace App\Http\Factories;

use App\Http\Repositories\ValidationRepo;
use App\User;

class ValidationFactory
{
    protected $validationRepo;

    protected $mailer;

    protected $resendAfter = 24;

    public function __construct(ValidationRepo $validationRepo)
    {
        $this->validationRepo = $validationRepo;
    }

    public function sendValidationMail(User $user, $force = false)
    {
        if (($user->validated || ! $this->shouldSend($user)) && ! $force) {
            return;
        }

        $token = $this->validationRepo->createValidation($user);

        $link = url('user/validate', [$token]);

        \Mail::to($user->email)->send(new \App\Mail\AccountValidation($link));
    }

    public function validateUser($token)
    {
        $validation = $this->validationRepo->getValidationByToken($token);

        if ($validation === null) {
            return null;
        }

        $user = User::find($validation->user_id);

        $user->validated = 1;

        $user->save();

        $this->validationRepo->deleteValidation($token);

        return $user;
    }

    private function shouldSend(User $user)
    {
        $validation = $this->validationRepo->getValidation($user);

        return $validation === null || strtotime($validation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}
