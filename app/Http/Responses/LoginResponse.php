<?php

namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if (is_null($user->employee_id)) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('pegawai.homepage');
    }
}
