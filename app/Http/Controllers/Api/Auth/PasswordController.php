<?php

namespace Permit\Http\Controllers\Api\Auth;

use Permit\Http\Requests\Api\Passwords\Change;
use Permit\Http\Requests\Api\Passwords\Forget;
use Permit\Mail\PasswordForgetMail;
use Permit\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Permit\Http\Controllers\Api\ApiController;

/**
 * RESTful Password Controller
 *
 * @Resource("Password", uri="/password")
 */
class PasswordController extends ApiController
{
    /**
     * /**
     * Change authenticated user's password.
     *
     * @POST("/change")
     *
     * @Versions({"v1"})
     *
     * @Request("password=bcdefg&new_password=abc123&new_password_confirmation=abc123", contentType="application/x-www-form-urlencoded")
     *
     * @Response(200,body={"message":" Password successfully changed","status_code":200})
     *
     * @Response(422,body={"message":" Current password does not match","status_code":422})
     */
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

    /**
     * /**
     * Sent password reset email
     *
     * @POST("/forget")
     *
     * @Versions({"v1"})
     *
     * @Request("email=foo@example.com", contentType="application/x-www-form-urlencoded")
     *
     * @Response(200,body={"message":" Password reset email successfully sent","status_code":200})
     */
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