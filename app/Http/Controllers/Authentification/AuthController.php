<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    protected $appGuard = [];

    public function __construct($appGuard)
    {
        $this->middleware('guest:admin,reception,technicien')->except('logout');

        $this->appGuard = $appGuard;
    }


    public function loginForm()
    {
        return view('theme.Authentification.Login.index');
    }

    public function logout(Request $request)
    {

        $this->guard(\ticketApp::activeGuard())->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect(route('admin:auth:login'));
    }

    protected function attemptLogin(Request $request): bool
    {
      //  dd($request->all(),$this->appGuard);
        $request->validate(
            ['authuser' => ['required', 'string', Rule::in($this->appGuard)]],
            [
                'authuser.required' => "s'il vous plaît choisir le type de user",
                'authuser.in' => "le type de user n'exist pas "
            ],
        );

        if (!$request->has('authuser') && !$request->filled('authuser')) {

            return false;
        }

        $guard = $request->authuser;

       // dd('Oui', $guard);

        return $this->guard($guard)->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * @return Guard|StatefulGuard
     */
    protected function guard($guard = 'admin')
    {
        return Auth::guard($guard);
    }

    /**
     * @return string
     */
    private function redirectTo()
    {
        return route('admin:home');
    }
}
