<?php

namespace App\Http\Controllers\Weight\Auth;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UserAuthInputRequest;
use App\Http\Requests\User\EmailExistsRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repos\User\UserRepo;
use App\Services\Firebase\FirebaseAuth;
use App\Services\User\UserAuthService;
use App\Services\User\UserInfoDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    public function register(UserAuthInputRequest $request, UserAuthService $userAuth)
    {
        $userAuth->register(new UserInfoDTO($request->email, $request->password));
        return redirect(RouteServiceProvider::HOME);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::HOME);
    }

    public function loginWithFirebase(Request $request, FirebaseAuth $auth, UserAuthService $userAuth)
    {
        $uid = $request->uid;
        $user = $auth->getUser($uid);

        if (!$user) {
            return 'user not found';
        }

        $userAuth->register(new UserInfoDTO($user->email, $user->uid, $user->displayName, now()));

        return redirect(RouteServiceProvider::HOME);
    }

    public function emailExists(EmailExistsRequest $request)
    {
        return $this->respondWithArray([
            'exists' => User::where('email', $request->email)->exists()
        ]);
    }

    public function update(UserUpdateRequest $request, UserRepo $repo)
    {
        $repo->updateInfo(
            $request->user(),
            $request->name,
            $request->birthday,
            $request->gender
        );

        return redirect()->back();
    }
}
