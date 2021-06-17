<?php

namespace App\Http\Controllers\Weight\Auth;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Providers\RouteServiceProvider;
use App\Services\User\UserAuthService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PasswordController extends ApiController
{
    public function resetPage(Request $request)
    {
        return Inertia::render('ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function change(ChangePasswordRequest $request, UserAuthService $service)
    {
        $service->resetPassword($request->user(), $request->password);
        return redirect()->back();
    }

    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email',]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect(RouteServiceProvider::HOME);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
