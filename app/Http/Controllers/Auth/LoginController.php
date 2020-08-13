<?php

namespace App\Http\Controllers\Auth;
use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
class LoginController extends Controller
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:company')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
    }

    protected function showLoginForm()
    {
        return view('login');
    }

    protected function showLoginFormAdmin()
    {
        return view('loginAdmin');
    }

    protected function validator(array $data, $role)
    {
        Validator::extend('Registered', function($attribute, $value, $parameters)
        {
            $aa = DB::table($parameters[0])->where([$parameters[1] => $value])->count();
            if($aa>0){
                return true;
            }
            else{
                return false;
            }
        }, 'Email Not Registered');

        return Validator::make($data, [
            'email'   => ['required','email', 'Registered:'.$role.'s,email', Rule::exists($role.'s')->where(function ($query) { $query->where('isActive', 1);})],
            'password' => ['required','min:6'],
        ],
        [
            'email.exists' => 'Account not acctivated yet.',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters.',
        ]);
    }

    public function LoginLogic(Request $request)
    {
        $role = $request->input('role');
        if($role=="user" || $role=="admin" || $role=="company")
        {

            $validator = $this->validator($request->all(), $role);
            if ($validator->fails())
            {
                return back()->withInput($request->only('email'))->with('role', $role)->withErrors($validator);
            }
            if (Auth::guard($role)->attempt(['email' => $request->email, 'password' => $request->password, 'isActive' => 1])) {
                if(Auth::guard($role)->check())
                {
                    return redirect()->intended('/'.$role.'');
                }
            }
            //return "FAILED";
            return back()->withInput($request->only('email', 'remember'))->withErrors(['invalid' => 'Invalid Credentials']);
        }
        else
        {
            return '404 - UnAuthorized Role';
        }
    }
}
