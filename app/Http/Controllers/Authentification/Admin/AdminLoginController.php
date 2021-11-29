<?php

namespace App\Http\Controllers\Authentification\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{


    use AuthenticatesUsers;

    public function __construct()
    {

        $this->middleware('guest:admin')->except('logout');
    }


    public function loginForm()
    {
        return view('theme.Authentification.Admin.Login.index');
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
            : redirect(route('home'));
    }

    protected function attemptLogin(Request $request): bool
    {
        if (!$request->has('guard') && !$request->filled('guard')) {

            return false;
        }

        /*$guard = $request->only('guard');

        if (!isset($guard) && !in_array($guard['guard'], $this->appGuard)) {

            return false;
        }*/
       // dd('Oui',$request);
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
        return Auth::guard('admin');
    }

    /**
     * @return string
     */
    private function redirectTo()
    {

        return route('admin:home');

    }
}
