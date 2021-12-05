<?php

namespace App\Http\Controllers\Authentification\Reception;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class ReceptionLoginController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {

        $this->middleware('guest:reception')->except('logout');
    }

    public function loginForm()
    {
        return view('theme.Authentification.Reception.Login.index');
    }

    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect(route('reception:auth:login'));
    }

    protected function attemptLogin(Request $request): bool
    {
        /*if (!$request->has('guard') && !$request->filled('guard')) {

            return false;
        }

        $guard = $request->only('guard');

        if (!isset($guard) && !in_array($guard['guard'], $this->appGuard)) {

            return false;
        }*/
        //dd('Oui',$request);
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * @return Guard|StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('reception');
    }

    private function redirectTo()
    {
        return route('reception:home');
    }
}
