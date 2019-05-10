<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Log;
use App\Http\Controllers\ApplicationController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\UserOauth;
use App\User;

use Illuminate\Http\Request;

class LoginController extends ApplicationController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return \Socialite::driver($provider)->redirect();
    }

    private function firstOrCreateUser($userSocialite){
        $user = User::where('email', $userSocialite->getEmail())->first();
        if ($user) return $user;
        return User::create([
            'name' => $userSocialite->getName(),
            'email' => $userSocialite->getEmail(),
            'picture' => $userSocialite->getAvatar()
        ]);
    }

    private function firstOrCreateOAuthUser($userSocialite, $provider){
        $userOauth = UserOauth::where('uuid', $userSocialite->getId())->first();
        if ($userOauth) return $userOauth;
        $user = Auth::guest() ? $this->firstOrCreateUser($userSocialite) : Auth::user();
        return UserOauth::create([
            'uuid' => $userSocialite->getId(),
            'name' => $userSocialite->getName(),
            'email' => $userSocialite->getEmail(),
            'avatar' => $userSocialite->getAvatar(),
            'user_id' => $user->id,
            'provider' => $provider
        ]);
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $userSocialite = \Socialite::driver($provider)->user();
        $userOauth = $this->firstOrCreateOAuthUser($userSocialite, $provider);
        Auth::login($userOauth->user);
        return redirect($this->redirectTo);
    }
}
