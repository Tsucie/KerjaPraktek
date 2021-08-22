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
        $this->validateLogin($request);
        // if ($request->has('toPage'))
        //     $this->redirectTo = $request->toPage;

        $this->attemptLogin($request);

        if (Auth::guard('customer')->check()) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
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
        // return $request->wantsJson()
        //     ? new JsonResponse([], 204)
        //     : redirect('/');
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
            'email' => 'required|string',
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
        // return $request->only($this->username(), 'cst_password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $resmsg = new ResponseMessage();
        $resmsg->code = 1;
        $resmsg->message = 'Login Berhasil. Hai '.auth()->guard('customer')->user()->cst_name.'!';
        return response()->json($resmsg);
        // return $request->wantsJson() ? new JsonResponse([], 204) : redirect()->intended($this->redirectTo);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Session::flash('error', 'Email atau Password Salah');
        // return redirect()->route('signin');
        $resmsg = new ResponseMessage();
        $resmsg->code = 0;
        $resmsg->message = 'Email atau Password Salah';
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
