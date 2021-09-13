<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ResponseMessage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if ($this->attemptLogin($request)) {
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
     * @return \Illuminate\Http\JsonResponse
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
     * Reset the customer account password
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPass(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'telp' => 'required|string',
            'nama' => 'required|string',
            'new_password' => 'required|string'
        ]);
        $resmsg = new ResponseMessage();
        try
        {
            $csQuery = Customer::query()->where('cst_email','=',$request->email);
            $cs = $csQuery->get();
            if ($cs->count() == 0) throw new Exception("Email tidak terdaftar!", 0);
            if ($cs[0]->cst_no_telp != $request->telp) throw new Exception("Nomor Telpon tidak terdaftar!", 0);
            if ($cs[0]->cst_name != $request->nama) throw new Exception("Nama tidak sesuai!", 0);
            
            if ($csQuery->update(['cst_password' => Hash::make($request->new_password)]) === 0)
                throw new Exception("Reset password gagal", 0);
            
            $resmsg->code = 1;
            $resmsg->message = "Password berhasil diubah";
        }
        catch (Exception $ex)
        {
            // $resmsg->code = 1;
            // $resmsg->message = 'Data Gagal Diubah';

            #region Code Testing
            $resmsg->code = $ex->getCode();
            $resmsg->message = $ex->getMessage();
            #endregion
        }
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
