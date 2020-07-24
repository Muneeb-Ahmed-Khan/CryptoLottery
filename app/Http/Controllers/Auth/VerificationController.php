<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Company;
use App\Admin;
use App\User;
use DB;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        /*
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        */

        $this->middleware('auth')->except('verify','resend');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }

    public function getEmail()
    {
        return user()->getEmailForVerification();
    }

       /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
	public function verify(Request $request)
	{
        $std;
        $table = $request->input('instanceOf');
        if($table == 'users')
        {
            $std = User::find($request->route('id'));
        }
        elseif($table == 'admins')
        {
            $std = Admin::find($request->route('id'));
        }
        elseif($table == 'companys')
        {
            $std = Company::find($request->route('id'));
        }
        else
        {
            return "FALSE INSTANCE";
        }

        if (! $request->hasValidSignature()) {
            return 'Invalid URL';
        }

        if (! hash_equals((string) $request->route('id'), (string) $std->getKey())) {
            return 'Invalid URL';
        }

        if (! hash_equals((string) $request->route('hash'), sha1($std->getEmailForVerification()))) {
            return 'Invalid URL';
        }

        if ($std->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($std->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        DB::update('update '.$table.' set isActive = ? where id = ?',[1,$std['id']]);

        return redirect($this->redirectPath())->with('verified', true);
    }

    public function resend(Request $request)
    {

        $std;
        $table = $request->route('role');
        if($table.'s' == 'companys')
        {
            $std = Company::where('email',$request->route('email')) -> first();
        }
        elseif($table.'s' == 'admins')
        {
            $std = Admin::where('email',$request->route('email')) -> first();
        }
        elseif($table.'s' == 'users')
        {
            $std = User::where('email',$request->route('email')) -> first();
        }
        else
        {
            return "FALSE INSTANCE";
        }
        if($std != null)
        {
            $std->sendEmailVerificationNotification();
            return redirect()->intended('/')->withErrors(["success" => "Check your inbox for Verification Email"]);
        }
        return redirect()->intended('/')->withErrors(["WrongEmail" => "Wrong Email Given"]);
    }
}
