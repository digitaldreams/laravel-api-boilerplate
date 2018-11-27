<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Passwords\Change;
use App\Http\Requests\Api\Passwords\Forget;
use App\Mail\PasswordForgetMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\ApiController;


class PasswordController extends ApiController
{

    public function change(Change $request)
    {
        if (Hash::check($request->get('password'), auth()->user()->password)) {
            auth()->user()->password = bcrypt($request->get('password'));
            auth()->user()->save();

            return $this->response->array([
                'message' => 'Password successfully changed',
                'status_code' => 200
            ]);

        } else {
            return $this->response->error('Current password does not match', 422);
        }

    }


    public function forget(Forget $request)
    {
        $user = User::where('email', $request->email)->first();
        Mail::to($user)->send(new PasswordForgetMail($user));

        return $this->response->array([
            'Password reset email successfully sent',
            'status_code' => 200
        ]);
    }
}