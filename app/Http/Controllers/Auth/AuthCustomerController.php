<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ResponseMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthCustomerController extends Controller
{
    public $redirectTo = '/';
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
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'cst_email';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $resmsg = new ResponseMessage();

        $this->validateLogin($request);

        $this->attemptLogin($request);

        if (Auth::guard('customer')->check()) {
            $resmsg->code = 1;
            $resmsg->message = 'Login Berhasil. Hai '.auth()->guard('customer')->user()->cst_name.'!';
            return $this->sendLoginResponse($request, $resmsg);
        }

        $resmsg->code = 0;
        $resmsg->message = 'Email atau Password Salah';
        return $this->sendFailedLoginResponse($resmsg);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $name = auth()->guard('customer')->user()->cst_name;

        $this->guard('customer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $resmsg = new ResponseMessage();
        $resmsg->code = 1;
        $resmsg->message = 'Logout Berhasil. Bye '.$name.'';
        return response()->json($resmsg);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard('customer')->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $data = [
            $this->username() => $request->email,
            'password' => $request->password
        ];
        return $data;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\ResponseMessage $resmsg
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request, $resmsg)
    {
        $request->session()->regenerate();
        return response()->json($resmsg);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  App\Models\ResponseMessage $resmsg
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendFailedLoginResponse($resmsg)
    {
        return response()->json($resmsg);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard($name = null)
    {
        return Auth::guard($name);
    }
}
