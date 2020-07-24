<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Admin;
use App\User;
use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
        $this->middleware('guest:company');
        $this->middleware('guest:admin');
        $this->middleware('guest:user');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $table)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.$table.''],   //unique:$table (check the $table table for email uniqueness)
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ],
         [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name should not be greater than 50 characters.',

            'email.required' => 'Name is required',
            'email.min' => 'Email must be at least 2 characters.',
            'email.max' => 'Email should not be greater than 50 characters.',
            'email.unique' => 'Email already Registered',

            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password must be same',
         ]);
    }

    public function RegisterLogic(Request $request)
    {
        $validator = $this->validator($request->all(),  'users');  //Second Parameter is TableName we sent to Validator
        if ($validator->fails())
        {
            return back()->withInput($request->only('email', 'name'))->withErrors($validator);
        }
        else
        {
            $model = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                ]);

            /*
                    in this case i have 3 tables in which i create instances according to the roles.

                    PROBLEM:

                    http://127.0.0.1:8000/email/verify/ID/SHA1(EMAIL)?expires=SOMEVALUE&signature=SOMEVALUE
                    The above link sent to me by the Laravel Default Email Verification Notification has no value for which
                    table should i store the value, i can get the [ID, Hashed Email, Expiry time, Signature value] from the link
                    but i don't have the table name to which i should compare or do some function with.

                    SOLUTION:

                    Goto:       ProjectName\vendor\laravel\framework\src\Illuminate\Auth\Notifications\VerifyEmail.php

                    Inside [protected function verificationUrl($notifiable)],   add one more line
                    'instanceOf' => $notifiable->getTable(),
                    below 'hash' attribute in Carbon Section. This line will add one more parameter to the URL that will be
                    instanceOf=TABLENAME,   Now, you can extract the table name from the URL and use it in Query.

                    Updated Link will be after this modification : http://127.0.0.1:8000/email/verify/ID/SHA1(EMAIL)?expires=SOMEVALUE&instanceOf=teachers&signature=SOMEVALUE

                    Now, we can get the [ID, Hashed Email, Expiry time, TABLENAME , Signature value] from the link
                    to Extract TABLENAME from this link use:
                    $table = $request->input('instanceOf');

                    CONCULSION:

                    Now, when you use $model->sendEmailVerificationNotification();  it will get the TABLENAME associated with this model
                    from config/auth.php, it its mentioned in that file as provider in guard array and send it along with other information to email.

            */

            $model->sendEmailVerificationNotification();
            return redirect('/register')->withErrors(["success" => "Check your inbox for Verification Email"]);
        }
    }


    public function RegisterLogicAdmin(Request $request)
    {
        $validator = $this->validator($request->all(),  'admins');  //Second Parameter is TableName we sent to Validator
        if ($validator->fails())
        {
            return back()->withInput($request->only('email', 'name'))->withErrors($validator);
        }
        else
        {
            $model = Admin::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                ]);

            //After user is created, it auto logins, but we will directly logout after user is created.
            Auth::logout();
            
            return redirect('/register/admin')->withErrors(["success" => "Please wait, while superUser Verify you."]);
        }
    }


    protected function showRegisterForm()
    {
        return view('register');
    }


    protected function showRegisterFormAdmin()
    {
        return view('registerAdmin');
    }
}
